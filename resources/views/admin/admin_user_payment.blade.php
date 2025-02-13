@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
사용자 결제 관리
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<div class="row pt-2" data-div-main="user_add">
    <input type="hidden" data-main-group-type2 value="{{ $group_type2 }}">
    <input type="hidden" data-main-teach-seq value={{ session()->get('teach_seq') }}>
    <input type="hidden" data-main-teach-name value="{{ session()->get('teach_name') }}">

    <div class="sub-title d-flex justify-content-between align-items-center">
        <h2 class="text-sb-42px">
            <img src="{{ asset('images/card_icon.svg') }}" width="76">
            <span class="me-2">결제관리</span>
        </h2>
        <ul class="d-inline-flex gap-2">
            <li>
                <button type="button" onclick="userPaymentTopTab(this)" data-btn-top="stay"
                    class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover px-32 primary-bg-mian-hover active">
                    결제대기(<span data-payment-stay-cnt="top">0</span>)</button>
            </li>
            <li>
                <button type="button" onclick="userPaymentTopTab(this)" data-btn-top="complete"
                    class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover px-32 primary-bg-mian-hover">
                    결제완료(<span data-payment-complete-cnt="top">0</span>)</button>
            </li>
        </ul>
    </div>
    <table class="w-100 table-serach-style table-border-xless table-h-92 div-shadow-style rounded-3">
        <colgroup>
            <col style="width: 33.33%;">
            <col style="width: 33.33%;">
            <col style="width: 33.33%;">
        </colgroup>
        <thead></thead>
        <tbody>
            <tr class="text-start">
                <td class="text-start p-4 scale-text-gray_06 py-4">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative align-items-center">
                        <select data-select-top="region" {{ $group_type2 !='general' ? 'disabled' : '' }}
                            onchange="userPaymentSelectTop(this, 'region')"
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
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0" alt=""
                            width="32" height="32">
                    </div>
                </td>
                <td class="text-start px-4">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative align-items-center">
                        <select data-select-top="team" {{ $group_type2 !='general' ? 'disabled' : '' }}
                            onchange="userPaymentSelectTop(this, 'team')"
                            class="border-none lg-select rounded-0 text-sb-20px {{ $group_type2 != 'general' ? 'scale-text-gray_05' : '' }} p-0 w-100">
                            @if ($group_type2 == 'general')
                            <option value="">소속 팀을 선택해주세요.</option>
                            @else
                            @if (!empty($team))
                            <option value="{{ $team->team_code }}">{{ $team->team_name }}</option>
                            @endif
                            @endif

                        </select>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0" alt=""
                            width="32" height="32">
                    </div>
                </td>
                <td class="text-start p-4 scale-text-gray_06">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative align-items-center">
                        <select data-select-top="teacher" onchange="userPaymentSelectTop(this, 'teacher')"
                            class="border-none lg-select rounded-0 text-sb-20px p-0 w-100">
                            @if($group_type2 == 'general' || $group_type2 == 'team')
                            <option value="">소속 선생님을 선택해주세요.</option>
                            @else
                            <option value="{{ session()->get('teach_seq') }}" selected>{{ session()->get('teach_name')
                                }}</option>
                            @endif
                        </select>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0" alt=""
                            width="32" height="32">
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center mt-120">
        <div class="d-flex align-items-center ">
            <p class="text-sb-28px scale-text-black me-4">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="8" width="24" height="16" rx="1.33333" fill="#FFD368" />
                    <rect x="4" y="10.668" width="24" height="2.66667" fill="white" />
                    <rect x="5.33594" y="18.668" width="8" height="1.33333" rx="0.666667" fill="white" />
                    <rect x="14.6641" y="18.668" width="1.33333" height="1.33333" rx="0.666667" fill="white" />
                </svg>
                결제 <span data-change-str>대기</span> 요약
            </p>
            {{-- <p class="scale-text-gray_05 text-m-20px">※ 아직 상담을 진행하지 않은 학생 정보는 <b
                    class="basic-text-positie">상담관리</b>
                페이지에서 확인해주세요.</p> --}}
        </div>
        <p class="scale-text-gray_05 text-m-20px">※ 상담이 진행된 건만 목록에 표시됩니다.</p>
    </div>
    <div class="content-block mt-32">
        {{-- 결제 요약 번들 시작 --}}
        <div class="row mx-0" data-bundle="payment_show">
            {{-- 총 결제 대기 --}}
            <div class="col" data-div-middle="stay">
                <div
                    class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/won_icon.svg') }}" width="32">
                        <p class="text-sb-24px  ms-1" data-payment-all-str>총 결제 대기</p>
                    </div>
                    <div class="">
                        <p class="text-sb-24px ">
                            <span>
                                <b class="text-sb-42px" data-cnt data-payment-stay-cnt="middle">0</b>건
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            {{-- 신규 회원 --}}
            <div class="col curosr-pointer" data-div-middle="new">
                <div
                    class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/won_icon.svg') }}" width="32">
                        <p class="text-sb-24px  ms-1">신규 회원</p>
                    </div>
                    <div class="">
                        <p class="text-sb-24px "><b class="text-sb-42px" data-cnt data-new-user-cnt="middle">0</b>건</p>
                    </div>
                </div>
            </div>
            {{-- 재등록 회원 --}}
            <div class="col curosr-pointer" data-div-middle="readd">
                <div
                    class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/won_icon.svg') }}" width="32">
                        <p class="text-sb-24px  ms-1">재등록 회원</p>
                    </div>
                    <div class="">
                        <p class="text-sb-24px "><b class="text-sb-42px " data-cnt data-readd-user-cnt="middle">0</b>건
                        </p>
                    </div>
                </div>
            </div>
            {{-- 오늘 결제 예정 --}}
            <div class="col curosr-pointer" data-div-middle="today_payment_yet">
                <div
                    class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/won_icon.svg') }}" width="32">
                        <p class="text-sb-24px  ms-1">오늘 결제 예정</p>
                    </div>
                    <div class="">
                        <p class="text-sb-24px "><b class="text-sb-42px" data-cnt data-today-payment-cnt="middle">0</b>건
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{-- 결제 요약 숨김 번들--}}
        <div class="row mx-0" data-bundle="payment_hidden" hidden>
            {{-- 총 결제 완료 --}}
            <div class="col curosr-pointer" data-div-middle="complete">
                <div
                    class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/won_icon.svg') }}" width="32">
                        <p class="text-sb-24px  ms-1" data-payment-all-str>총 결제 완료</p>
                    </div>
                    <div class="">
                        <p class="text-sb-24px ">
                            <b class="text-sb-42px" data-cnt data-payment-complete-cnt="middle">0</b>건
                        </p>
                    </div>
                </div>
            </div>
            {{-- 만료임박 회원 --}}
            <div class="col curosr-pointer" data-div-middle="expire">
                <div
                    class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/won_icon.svg') }}" width="32">
                        <p class="text-sb-24px mx-1">만료임박 회원</p>
                    </div>
                    <div class="">
                        <p class="text-sb-24px "><b class="text-sb-42px" data-cnt data-expire-user-cnt="middle">0</b>건
                        </p>
                    </div>
                </div>
            </div>
            {{-- 정기 결제 --}}
            <div class="col curosr-pointer" data-div-middle="regular_payment">
                <div
                    class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/won_icon.svg') }}" width="32">
                        <p class="text-sb-24px mx-1">정기 결제</p>
                    </div>
                    <div class="">
                        <p class="text-sb-24px "><b class="text-sb-42px" data-cnt
                                data-regular-payment-cnt="middle">0</b>건</p>
                    </div>
                </div>
            </div>
            {{-- 오늘 결제 예정 --}}
            <div class="col curosr-pointer" data-div-middle="today_payment_yet">
                <div
                    class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/won_icon.svg') }}" width="32">
                        <p class="text-sb-24px mx-1">오늘 결제 예정</p>
                    </div>
                    <div class="">
                        <p class="text-sb-24px "><b class="text-sb-42px" data-cnt data-today-payment-cnt="middle">0</b>건
                        </p>
                    </div>
                </div>
            </div>
            {{-- 신규 배정 회원 --}}
            <div class="col curosr-pointer" data-div-middle="new_yet">
                <div
                    class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/won_icon.svg') }}" width="32">
                        <p class="text-sb-24px mx-1">신규 배정 회원</p>
                    </div>
                    <div class="">
                        <p class="text-sb-24px "><b class="text-sb-42px" data-cnt data-new-yet-cnt="middle">0</b>건</p>
                    </div>
                </div>
            </div>
            {{-- 재등록 예정 회원 --}}
            <div class="col curosr-pointer" data-div-middle="readd_yet">
                <div
                    class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/won_icon.svg') }}" width="32">
                        <p class="text-sb-24px mx-1">재등록 예정 회원</p>
                    </div>
                    <div class="">
                        <p class="text-sb-24px "><b class="text-sb-42px" data-cnt data-readd-yet-cnt="middle">0</b>건</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-end mt-80 mb-32">
        <div class="row mx-0" data-div-serach-date hidden>
            <label class="col-auto d-inline-block select-wrap select-icon h-62">
                <select
                    onchange="userPaymentSelectDateType(this, '[data-search-start-date]','[data-search-end-date]');userPaymentListCounselSelect();"
                    class="date-change rounded-pill ps-4 border-gray sm-select text-sb-20px me-2 h-62">
                    <option value="">기간설정</option>
                    <option value="-1">오늘로보기</option>
                    <option value="0">1주일전</option>
                    <option value="1">1개월전</option>
                    <option value="2">3개월전</option>
                </select>
            </label>
            <div class="col-auto h-center p-3 border rounded-pill">
                <img src="{{ asset('images/calendar_gray_icon.svg') }}" class="me-2">
                {{-- :날짜시간 PICKER 2 --}}
                <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start" style="height: 20px;">
                    <div class="h-center justify-content-between">
                        <div data-date onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                            type="text" class="text-m-20px text-start scale-text-gray_05" readonly="" placeholder="">
                            {{-- 상담시작일시 --}}
                            {{ date('Y.m.d') }}
                        </div>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                    </div>
                    <input type="date" style="width: 80px;height: 0.5px;" data-search-start-date
                        oninput="userPaymentDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                </div>
                ~
                <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start" style="height: 20px;">
                    <div class="h-center justify-content-between">
                        <div data-date onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                            type="text" class="text-m-20px text-start scale-text-gray_05" readonly="" placeholder="">
                            {{-- 상담시작일시 --}}
                            {{ date('Y.m.d') }}
                        </div>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                    </div>
                    <input type="date" style="width: 80px;height: 0.5px;" data-search-end-date
                        oninput="userPaymentDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                </div>

            </div>
        </div>

        <div class="col text-end">
            <label class="d-inline-block select-wrap select-icon h-62">
                <select id="select2" class="date-change rounded-pill ps-4 border-gray sm-select text-sb-20px me-2 h-62">
                    <option value="">검색기준</option>
                    <option value="0">1</option>
                    <option value="1">2</option>
                    <option value="2">3</option>
                </select>
            </label>
            <label class="label-search-wrap">
                <input type="text" class="ms-search border-gray rounded-pill text-m-20px" placeholder="검색어를 입력해주세요.">
            </label>
        </div>
    </div>

    <div id="myTable_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">
        <div class="row justify-content-between">
            <div class="col-md-auto me-auto "></div>
            <div class="col-md-auto ms-auto "></div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-12 ">
                <table class="table-style w-100" style="min-width: 100%;">
                    <colgroup>
                        <col style="width: 80px;">

                    </colgroup>
                    <thead class="">
                        <tr class="text-sb-20px modal-shadow-style rounded">
                            <th style="width: 80px" class="is_leader is_general" hidden>선생님</th>
                            <th>이용권상태</th>
                            <th class="is_complete" hidden>주문번호</th>
                            <th>학생이름/아이디</th>
                            <th>결제상태</th>
                            <th>최근상담일</th>
                            <th>결제수단</th>
                            <th class="">결제일</th>
                            <th class="is_complete" hidden>결제금액</th>
                            <th class="is_complete" hidden>승인번호</th>
                            <th class="is_due">결제예정일</th>
                            <th class="is_due">결제예정금액</th>
                            <th>현재상품명</th>
                            <th>이용기간</th>
                            <th>잔여일수</th>
                            <th hidden>이용권변경</th>
                            <th class="is_due">결제관리</th>
                            <th class="is_complete">상세</th>
                        </tr>
                    </thead>
                    <tbody data-bundle="tby_payments">
                        <tr class="text-m-20px h-104" data-row="copy" hidden>
                            <input type="hidden" data-student-seq>
                            <input type="hidden" data-regular-date>
                            <input type="hidden" data-goods-seq>
                            <input type="hidden" data-is-regular>

                            <td class="is_leader is_general" hidden>
                                <p data-teach-name>박선생</p>
                                <p>(<span data-group-name>상담</span>)</p>
                            </td>
                            <td class="scale-text-gray_05">
                                <span data-student-type-detail data-text="#이용권상태"
                                    class="rounded-pill basic-bg-positie text-sb-16px ps-12 pe-12 py-1 scale-text-white">신규</span>
                            </td>
                            <td class="is_complete" hidden>
                                <p data-payment-seq data-text="#주문번호"></p>
                            </td>
                            <td class="scale-text-gray_05">
                                <p class=""><span data-student-name data-text="#학생이름"></span>(<span data-student-id data-text="ID"></span>)</p>
                                <p>(학생/<span data-school-name data-text="학교"></span>)</p>
                            </td>
                            <td class="scale-text-gray_05">
                                <span data-status-str data-text="#결제상태"></span>
                            </td>
                            <td class="scale-text-gray_05">
                                <span data-recnt-counsel-date data-text="#최근상담일"></span>
                            </td>
                            <td class="scale-text-gray_05">
                                <p data-regular-type data-text="#결제수단"></p>
                                <p>(<span data-card-name data-text="#카드이름"></span>/<span data-card-inst data-text="일시불"></span>)</p>
                            </td>
                            <td class="scale-text-gray_05">
                                <span data-payment-due-date data-text="#결제예정일"></span>
                            </td>
                            <td class="scale-text-gray_05">
                                <p class="" data-payment-date data-text="#결제일"></p>
                            </td>
                            <td class="scale-text-gray_05 is_complete" hidden>
                                <p class="scale-text-black" data-payment-amount data-text="#결제금액"></p>
                            </td>
                            <td class="scale-text-gray_05 is_complete" hidden>
                                <p class="" data-payment-approval-number data-text="#승인번호"></p>
                            </td>

                            <td class="scale-text-gray_05">
                                <p class="scale-text-black" data-payment-due-amount data-text="#결제예정금액"></p>
                            </td>
                            <td class="scale-text-gray_05">
                                <p class="" data-goods-name data-text="#초등베이직"></p>
                                <p data-goods-period data-text="#6개월(월납)"></p>
                            </td>
                            <td class="scale-text-gray_05">
                                <p class="" data-goods-start-date data-text="24.02.01 부터"></p>
                                <p data-goods-end-date data-text="24.02.01 까지"></p>
                            </td>
                            <td class="scale-text-gray_05">
                                <p class="studyColor-text-studyComplete" data-goods-remain-date data-text="0일"></p>
                            </td>
                            <td class="scale-text-gray_05 is_general is_leader" hidden>
                                <button type="button" onclick="userPaymentModalShow(this, 'change');"
                                    class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">이용권변경</button>
                            </td>
                            <td class="scale-text-gray_05">
                                <button type="button" data-btn-payment onclick="userPaymentModalShow(this, 'payment');"
                                    class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">결제하기</button>
                                <p data-is-regular-str></p>
                            </td>
                            {{-- 상세 --}}
                            <td class="scale-text-gray_05 is_complete" hidden>
                                <button type="button" data-btn-payment-detail onclick="userPaymentDetailPage(this)"
                                    class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">상세보기</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="all-center mt-52">
        <div class=""></div>
        <div class="col-auto">
            {{-- :페이징 태그--}}
            <div class="col d-flex justify-content-center">
                <ul class="pagination col-auto" data-page="1" hidden>
                    <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1"
                        onclick="userPaymentPageFunc('1', 'prev')">
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                    </button>
                    <li class="page-item" hidden>
                        <a class="page-link" onclick="">0</a>
                    </li>
                    <span class="page" data-page-first="1" hidden onclick="userPaymentPageFunc('1', this.innerText);"
                        disabled>0</span>
                    <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1"
                        onclick="userPaymentPageFunc('1', 'next')" data-is-next="0">
                        <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                    </button>
                </ul>
            </div>
        </div>
    </div>
    {{-- 160px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>

    {{-- 모달 / 결제하기 --}}
    <div class="modal fade" id="user_payment_modal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display:none;" aria-modal="true" role="dialog">
        <div class="modal-dialog rounded" style="max-width: 592px;">
        <div class="modal-content border-none rounded p-3 modal-shadow-style">
          <div class="modal-header border-bottom-0">
            <h1 class="modal-title fs-5 text-b-24px" id="">
              <span data-student-name data-text="홍길동"></span>(<span data-student-type data-text="#만료회원"></span>)의
              <span data-title-after>결제 예정정보 변경</span>
            </h1>
            <button type="button" style="width: 32px;height: 32px;"
            class="btn-close close-btn" data-bs-dismiss="modal"  aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="text-sb-20px mb-3">이용권을 선택해주세요.</p>
            <div class="row content-block row-gap-3">
                @if(!empty($goods))
                    @foreach ($goods as $good)
                      <div class="col-6" onclick="userPaymentModalGoodsSel(this);" data-goods-row="{{ $good->id }}">
                        <div class="px-4 py-3 rounded-3 scale-text-gray_05 scale-text-white-hover primary-bg-mian-hover border-gray border-gray-hover-less">
                          <p class="text-sb-24px mb-12">{{ number_format($good->goods_price) }}원</p>
                          <p class="text-m-20px">{{ $good->goods_name }}({{ $good->is_auto_pay == 'Y' ? '월납':'완납' }})</p>
                        </div>
                        <input type="hidden" data-goods-seq value="{{ $good->id}}" >
                        <input type="hidden" data-goods-price value="{{ $good->goods_perice }}" >
                        <input type="hidden" data-goods-is-auto-pay="1" value="{{ $good->is_auto_pay }}" >
                      </div>
                    @endforeach
                @endif
            </div>
            <p class="text-sb-20px mt-32 mb-3">직접입력</p>
            <div class="row w-100 border-gray rounded-3 mb-52">
              <div class="col-10 p-0">
                <label class="label-input-wrap w-100">
                  <input type="text" data-input-price
                  class="smart-ht-search p-2 rounded-start border-none text-r-20px w-100 text-start px-4"  placeholder="금액을 입력해주세요.">
                </label>
              </div>
              <div class="col-2 p-0">
                <label class="label-input-wrap w-100 scale-text-black">
                  <input type="text" class="smart-ht-search rounded-end border-none scale-bg-white  text-m-20px w-100 text-center" placeholder="" disabled value="원">
                </label>
              </div>
            </div>
            <p class="text-sb-20px mb-3">이용권을 시작할 날짜를 선택해주세요.</p>
            <div class="h-center p-3 border rounded-3 mb-3">
                <img src="{{ asset('images/calendar_gray_icon.svg') }}" class="me-2">
                <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start" style="height: 20px;">
                    <div class="h-center justify-content-between">
                        <div  data-date onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()" type="text"
                        class="text-m-20px text-start scale-text-gray_05" readonly="" placeholder="">
                            {{-- 상담시작일시 --}}
                            {{ date('Y.m.d') }}
                        </div>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                    </div>
                    <input type="date" style="width: 80px;height: 0.5px;" data-goods-start-date
                    oninput="userPaymentDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <p class="text-sb-20px mb-3">결제수단을 선택해주세요.</p>
            <div class="d-inline-block select-wrap select-icon w-100 mb-12">
              <select class="border-gray lg-select text-sb-20px h-62 w-100" data-goods-is-auto-pay="2">
                <option value="Y">정기결제</option>
                <option value="N">완납결제</option>
              </select>
            </div>
            <p class="text-sb-20px mb-3 is_card" hidden>등록된 결제 정보</p>
            <div class="is_card" hidden>
              <svg width="528" height="228" viewBox="0 0 528 228" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="402" height="228" rx="12" fill="#4D9EEE"/>
                <path d="M30.9141 147.523C30.9076 148.305 31.0605 149.057 31.373 149.779C31.6921 150.495 32.1641 151.13 32.7891 151.684C33.4206 152.237 34.1823 152.66 35.0742 152.953L33.9023 154.652C32.9844 154.32 32.1836 153.842 31.5 153.217C30.8229 152.592 30.2858 151.859 29.8887 151.02C29.4915 151.99 28.9284 152.829 28.1992 153.539C27.4701 154.249 26.5911 154.789 25.5625 155.16L24.4102 153.441C25.3411 153.122 26.1289 152.667 26.7734 152.074C27.4245 151.475 27.9128 150.792 28.2383 150.023C28.5638 149.249 28.7266 148.435 28.7266 147.582V146H30.9141V147.523ZM27.1055 156.059H38.5117V162.523H27.1055V156.059ZM36.3633 160.766V157.797H29.2539V160.766H36.3633ZM36.3438 145.082H38.5117V149.242H40.9727V151.02H38.5117V155.336H36.3438V145.082ZM47.9359 147.621C47.9294 148.435 48.0824 149.206 48.3949 149.936C48.7139 150.658 49.1859 151.296 49.8109 151.85C50.4359 152.396 51.1977 152.81 52.0961 153.09L50.9242 154.789C49.9802 154.451 49.1697 153.972 48.4926 153.354C47.8155 152.735 47.2849 151.999 46.9008 151.146C46.5036 152.13 45.934 152.982 45.1918 153.705C44.4496 154.421 43.5544 154.965 42.5063 155.336L41.3539 153.578C42.3044 153.266 43.1085 152.813 43.766 152.221C44.4236 151.628 44.9151 150.948 45.2406 150.18C45.5727 149.405 45.7419 148.585 45.7484 147.719V146.039H47.9359V147.621ZM44.2836 159.34C44.2771 158.63 44.5147 158.021 44.9965 157.514C45.4783 156.999 46.1684 156.609 47.0668 156.342C47.9717 156.075 49.0427 155.941 50.2797 155.941C51.5232 155.941 52.5974 156.075 53.5023 156.342C54.4073 156.609 55.1007 156.999 55.5824 157.514C56.0707 158.021 56.3148 158.63 56.3148 159.34C56.3148 160.049 56.0707 160.658 55.5824 161.166C55.1007 161.674 54.4073 162.058 53.5023 162.318C52.5974 162.585 51.5232 162.719 50.2797 162.719C49.0427 162.719 47.9717 162.585 47.0668 162.318C46.1684 162.058 45.4783 161.674 44.9965 161.166C44.5147 160.658 44.2771 160.049 44.2836 159.34ZM46.432 159.34C46.4255 159.874 46.7576 160.287 47.4281 160.58C48.0987 160.867 49.0492 161.013 50.2797 161.02C51.5167 161.013 52.4704 160.867 53.141 160.58C53.8116 160.287 54.1469 159.874 54.1469 159.34C54.1469 158.786 53.8116 158.363 53.141 158.07C52.477 157.777 51.5232 157.628 50.2797 157.621C49.0427 157.628 48.0889 157.777 47.4184 158.07C46.7543 158.363 46.4255 158.786 46.432 159.34ZM50.5727 148.5H54.0883V145.082H56.2563V155.473H54.0883V150.277H50.5727V148.5ZM67.5359 146.82C67.5294 148.76 67.3016 150.508 66.8523 152.064C66.4096 153.614 65.6023 155.059 64.4305 156.4C63.2651 157.742 61.644 158.93 59.5672 159.965L58.3758 158.324C59.8732 157.589 61.1004 156.778 62.0574 155.893C63.0145 155.007 63.7566 154.031 64.284 152.963L58.7859 153.48L58.5125 151.605L64.9383 151.205C65.1661 150.372 65.3126 149.496 65.3777 148.578H59.4305V146.82H67.5359ZM70.0164 145.082H72.1844V151.996H74.8016V153.773H72.1844V162.699H70.0164V145.082ZM89.5773 155.102H77.1945V146.625H89.4406V148.383H79.343V153.363H89.5773V155.102ZM75.2414 158.695H91.3742V160.492H75.2414V158.695Z" fill="white"/>
                <path d="M28.3359 193.895L28.4922 191.082L26.1289 192.625L25.2891 191.199L27.8086 189.93L25.2891 188.641L26.1289 187.215L28.4922 188.758L28.3359 185.945H29.9961L29.8398 188.758L32.2031 187.215L33.0234 188.641L30.5234 189.93L33.0234 191.199L32.2031 192.625L29.8398 191.082L29.9961 193.895H28.3359ZM38.268 193.895L38.4242 191.082L36.0609 192.625L35.2211 191.199L37.7406 189.93L35.2211 188.641L36.0609 187.215L38.4242 188.758L38.268 185.945H39.9281L39.7719 188.758L42.1352 187.215L42.9555 188.641L40.4555 189.93L42.9555 191.199L42.1352 192.625L39.7719 191.082L39.9281 193.895H38.268ZM48.2 193.895L48.3563 191.082L45.993 192.625L45.1531 191.199L47.6727 189.93L45.1531 188.641L45.993 187.215L48.3563 188.758L48.2 185.945H49.8602L49.7039 188.758L52.0672 187.215L52.8875 188.641L50.3875 189.93L52.8875 191.199L52.0672 192.625L49.7039 191.082L49.8602 193.895H48.2ZM58.132 193.895L58.2883 191.082L55.925 192.625L55.0852 191.199L57.6047 189.93L55.0852 188.641L55.925 187.215L58.2883 188.758L58.132 185.945H59.7922L59.6359 188.758L61.9992 187.215L62.8195 188.641L60.3195 189.93L62.8195 191.199L61.9992 192.625L59.6359 191.082L59.7922 193.895H58.132ZM72.4102 193.895L72.5664 191.082L70.2031 192.625L69.3633 191.199L71.8828 189.93L69.3633 188.641L70.2031 187.215L72.5664 188.758L72.4102 185.945H74.0703L73.9141 188.758L76.2773 187.215L77.0977 188.641L74.5977 189.93L77.0977 191.199L76.2773 192.625L73.9141 191.082L74.0703 193.895H72.4102ZM82.3422 193.895L82.4984 191.082L80.1352 192.625L79.2953 191.199L81.8148 189.93L79.2953 188.641L80.1352 187.215L82.4984 188.758L82.3422 185.945H84.0023L83.8461 188.758L86.2094 187.215L87.0297 188.641L84.5297 189.93L87.0297 191.199L86.2094 192.625L83.8461 191.082L84.0023 193.895H82.3422ZM92.2742 193.895L92.4305 191.082L90.0672 192.625L89.2273 191.199L91.7469 189.93L89.2273 188.641L90.0672 187.215L92.4305 188.758L92.2742 185.945H93.9344L93.7781 188.758L96.1414 187.215L96.9617 188.641L94.4617 189.93L96.9617 191.199L96.1414 192.625L93.7781 191.082L93.9344 193.895H92.2742ZM102.206 193.895L102.363 191.082L99.9992 192.625L99.1594 191.199L101.679 189.93L99.1594 188.641L99.9992 187.215L102.363 188.758L102.206 185.945H103.866L103.71 188.758L106.073 187.215L106.894 188.641L104.394 189.93L106.894 191.199L106.073 192.625L103.71 191.082L103.866 193.895H102.206ZM116.484 193.895L116.641 191.082L114.277 192.625L113.438 191.199L115.957 189.93L113.438 188.641L114.277 187.215L116.641 188.758L116.484 185.945H118.145L117.988 188.758L120.352 187.215L121.172 188.641L118.672 189.93L121.172 191.199L120.352 192.625L117.988 191.082L118.145 193.895H116.484ZM126.416 193.895L126.573 191.082L124.209 192.625L123.37 191.199L125.889 189.93L123.37 188.641L124.209 187.215L126.573 188.758L126.416 185.945H128.077L127.92 188.758L130.284 187.215L131.104 188.641L128.604 189.93L131.104 191.199L130.284 192.625L127.92 191.082L128.077 193.895H126.416ZM136.348 193.895L136.505 191.082L134.141 192.625L133.302 191.199L135.821 189.93L133.302 188.641L134.141 187.215L136.505 188.758L136.348 185.945H138.009L137.852 188.758L140.216 187.215L141.036 188.641L138.536 189.93L141.036 191.199L140.216 192.625L137.852 191.082L138.009 193.895H136.348ZM146.28 193.895L146.437 191.082L144.073 192.625L143.234 191.199L145.753 189.93L143.234 188.641L144.073 187.215L146.437 188.758L146.28 185.945H147.941L147.784 188.758L150.148 187.215L150.968 188.641L148.468 189.93L150.968 191.199L150.148 192.625L147.784 191.082L147.941 193.895H146.28ZM160.559 193.895L160.715 191.082L158.352 192.625L157.512 191.199L160.031 189.93L157.512 188.641L158.352 187.215L160.715 188.758L160.559 185.945H162.219L162.062 188.758L164.426 187.215L165.246 188.641L162.746 189.93L165.246 191.199L164.426 192.625L162.062 191.082L162.219 193.895H160.559ZM170.491 193.895L170.647 191.082L168.284 192.625L167.444 191.199L169.963 189.93L167.444 188.641L168.284 187.215L170.647 188.758L170.491 185.945H172.151L171.995 188.758L174.358 187.215L175.178 188.641L172.678 189.93L175.178 191.199L174.358 192.625L171.995 191.082L172.151 193.895H170.491ZM180.423 193.895L180.579 191.082L178.216 192.625L177.376 191.199L179.895 189.93L177.376 188.641L178.216 187.215L180.579 188.758L180.423 185.945H182.083L181.927 188.758L184.29 187.215L185.11 188.641L182.61 189.93L185.11 191.199L184.29 192.625L181.927 191.082L182.083 193.895H180.423Z" fill="white"/>
                <rect x="415" y="1" width="400" height="226" rx="11" fill="#F9F9F9" stroke="#E5E5E5" stroke-width="2" stroke-dasharray="8 8"/>
              </svg>
            </div>
            <div class="d-inline-block select-wrap select-icon w-100 mb-12 mt-4 is_card" hidden>
              <select data-regular-date
              class="border-gray lg-select text-sb-20px h-62 w-100">
                {{-- 매월 N일(1~31까지 반복) --}}
                <option value="">없음</option>
                @for ($i = 1; $i <= 31; $i++)
                    <option value="{{ $i }}">매월 {{ $i }}일</option>
                @endfor
              </select>
            </div>
            <p class="text-sb-20px mb-52 mt-3 no_card" hidden>
                <b class="studyColor-text-studyComplete">※ 등록된 결제 정보가 없습니다. 학부모님께 결제정보를 요청하세요.</b>
            </p>
          </div>
          <div class="scale-bg-gray_01" style="margin: 0 -16px;">
            <div class="d-flex justify-content-between align-items-center align-items-center h-104 px-32">
              <p class="text-sb-20px">예상 결제 금액</p>
              <p class="text-sb-24px" data-payment-amount="" ></p>
            </div>
          </div>
          <div class="modal-footer border-top-0 p-0 pb-2 mt-52">
            <div class="row w-100 ">

              <div class="col-12 ">
                <button type="button" data-btn-payment-modal
                class="btn-lg-primary text-sb-24px rounded scale-text-gray_05 scale-bg-gray_01 primary-bg-mian-hover scale-text-white-hover w-100 all-center cursor-pointer">결제정보를 확인해주세요.</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- 상세페이지 이동 폼 --}}
    <form action="/manage/user/payment/detail" data-form-payment-detail hidden>
        @csrf
        <input name="payment_seq">
        <input name="student_seq">
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const group_type2 = document.querySelector('[data-main-group-type2]').value;
        if(group_type2 == 'leader'){
            const data_select_top = document.querySelector('[data-select-top="team"]');
            data_select_top.onchange();
        }
    });

    // 상단 결제대기, 결제완료 클릭시.
    function userPaymentTopTab(vthis){
        //data-btn-top 모두 비활성화
        const btns = document.querySelectorAll('[data-btn-top]');
        btns.forEach(function(btn){
            btn.classList.remove('active');
        });
        vthis.classList.add('active');
        // const act_type = vthis.getAttribute('data-btn-top');

        userPaymentMiddleInsertPlace();
        userPaymentBtmList();
    }

    // 상단 select_tag(el) 선택시
        function userPaymentSelectTop(vthis, type) {
            if (type == 'region' || type == 'region_modal') {
                const region_seq = vthis.value;
                userPaymentTeamSelect(region_seq);
            } else if (type == 'team' || type == 'team_modal') {
                const team_code = vthis.value;
                userPaymentTeacherSelect(team_code);
            } else if (type == 'teacher') {
                userPaymentMiddleInsertPlace();
                userPaymentBtmList();
            }
        }

        // 본부 선택시 팀 SELECT
        function userPaymentTeamSelect(region_seq, is_btm) {
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
        function userPaymentTeacherSelect(team_code){
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
                    const my_type2 = document.querySelector('[data-main-group-type2]').value;
                    if(my_type2 != 'run' && my_type2 != 'leader'){
                        const option = document.createElement('option');
                        option.value = document.querySelector('[data-main-teach-seq]').value;
                        option.innerText = document.querySelector('[data-main-teach-name]').value;
                        option.setAttribute('data-group-type2', my_type2);
                        select_teacher.appendChild(option);
                    }
                    const teachers = result.resultData;
                    teachers.forEach(function(teacher){
                        const option = document.createElement('option');
                        option.value = teacher.id;
                        option.innerText = teacher.teach_name;
                        if(teacher.group_type2 != 'run' && teacher.group_type2 != 'leader'){
                            return;
                        }else{
                            option.setAttribute('data-group-type2', teacher.group_type2);
                            option.setAttribute('data-group-type3', teacher.group_type3);
                            select_teacher.appendChild(option);
                        }
                    });
                }
            });
        }

        // 미들의 위치를 group_type2, group_type3 에 따라 넣어주기.
        function userPaymentMiddleInsertPlace(){
            const teacher_el = document.querySelector('[data-select-top="teacher"]');
            const group_type2 = teacher_el.selectedOptions[0].getAttribute('data-group-type2');
            const group_type3 = teacher_el.selectedOptions[0].getAttribute('data-group-type3');

            const is_complete = document.querySelector('[data-btn-top="complete"]').classList.contains('active');
            // 숨김 / 보이기 번들
            const bundle_show = document.querySelector('[data-bundle="payment_show"]');
            const bundle_hidden = document.querySelector('[data-bundle="payment_hidden"]');

            // 하위내용.
            const div_stay = document.querySelector('[data-div-middle="stay"]');
            const div_new = document.querySelector('[data-div-middle="new"]');
            const div_readd = document.querySelector('[data-div-middle="readd"]');
            const div_today_payment_yet = document.querySelector('[data-div-middle="today_payment_yet"]');
            const div_complete = document.querySelector('[data-div-middle="complete"]');
            const div_expire = document.querySelector('[data-div-middle="expire"]');
            const div_regular_payment = document.querySelector('[data-div-middle="regular_payment"]');
            const div_new_yet = document.querySelector('[data-div-middle="new_yet"]');
            const div_readd_yet = document.querySelector('[data-div-middle="readd_yet"]');

            const arr_div = {};
            arr_div['bundle_show'] = bundle_show;
            arr_div['bundle_hidden'] = bundle_hidden;

            arr_div['stay'] = div_stay;
            arr_div['new'] = div_new;
            arr_div['readd'] = div_readd;
            arr_div['today_payment_yet'] = div_today_payment_yet;
            arr_div['complete'] = div_complete;
            arr_div['expire'] = div_expire;
            arr_div['regular_payment'] = div_regular_payment;
            arr_div['new_yet'] = div_new_yet;
            arr_div['readd_yet'] = div_readd_yet;

            // 모두 hidden 번들에 넣어준다.
            userPaymentMiddleTagHiddenInput(arr_div);

            // 타입에 따라서 보여줄것을 보여준다.
            userPaymentMiddleTagShowInput(arr_div, group_type2, group_type3, is_complete);

            // 결제 대기 이면 조건 날짜 검색 숨김 / 완료이면 보이기.
            const div_serach_date = document.querySelector('[data-div-serach-date]');
            const change_str = document.querySelector('[data-change-str]');

            if(is_complete){
                div_serach_date.hidden = false;
                change_str.innerText = '완료';
            }else{
                div_serach_date.hidden = true;
                change_str.innerText = '대기';
            }
        }

        // 미들(요약) 모두 숨기기
        function userPaymentMiddleTagHiddenInput(arr_div){
            arr_div['bundle_hidden'].appendChild(arr_div['stay']);
            arr_div['bundle_hidden'].appendChild(arr_div['new']);
            arr_div['bundle_hidden'].appendChild(arr_div['readd']);
            arr_div['bundle_hidden'].appendChild(arr_div['today_payment_yet']);
            arr_div['bundle_hidden'].appendChild(arr_div['complete']);
            arr_div['bundle_hidden'].appendChild(arr_div['expire']);
            arr_div['bundle_hidden'].appendChild(arr_div['regular_payment']);
            arr_div['bundle_hidden'].appendChild(arr_div['new_yet']);
            arr_div['bundle_hidden'].appendChild(arr_div['readd_yet']);

            // arr_div['bundle_hidden'] 안에 있는 하위 자식 노드의 onclick을 삭제
            const divs = arr_div['bundle_hidden'].querySelectorAll('[data-div-middle]');
            divs.forEach(function(div){
                div.onclick = null;
                div.querySelector('[data-cnt]').innerText = '0';
            });
            const bundle = document.querySelector('[data-bundle="tby_payments"]').closest('table');
            bundle.querySelectorAll('.is_due, .is_complete, .is_general, .is_leader').forEach(function(el){
                el.hidden = true;
            });
        }

        // 미들(요약) 보이기
        function userPaymentMiddleTagShowInput(arr_div, group_type2, group_type3, is_complete){
            if(group_type2 == 'general' || group_type2 == 'leader'){
                if(is_complete){
                    arr_div['bundle_show'].appendChild(arr_div['complete']);
                    arr_div['bundle_show'].appendChild(arr_div['new']);
                    arr_div['bundle_show'].appendChild(arr_div['readd']);
                }else{
                    arr_div['bundle_show'].appendChild(arr_div['stay']);
                    arr_div['bundle_show'].appendChild(arr_div['new']);
                    arr_div['bundle_show'].appendChild(arr_div['readd_yet']);
                    arr_div['bundle_show'].appendChild(arr_div['today_payment_yet']);
                }
            }
            // 총괄 / 팀장 제외
            else if(group_type2 == 'run'){
                // 상담선생님
                if(group_type3 == 'counsel'){
                    if(is_complete){
                        arr_div['bundle_show'].appendChild(arr_div['complete']);
                        arr_div['bundle_show'].appendChild(arr_div['new']);
                        arr_div['bundle_show'].appendChild(arr_div['readd']);
                    }else{
                        arr_div['bundle_show'].appendChild(arr_div['stay']);
                        arr_div['bundle_show'].appendChild(arr_div['new']);
                        arr_div['bundle_show'].appendChild(arr_div['readd']);
                        arr_div['bundle_show'].appendChild(arr_div['today_payment_yet']);
                    }
                }
                // 그외 선생님 / 담당
                else{
                    if(is_complete){
                        arr_div['bundle_show'].appendChild(arr_div['complete']);
                        arr_div['bundle_show'].appendChild(arr_div['new']);
                        arr_div['bundle_show'].appendChild(arr_div['readd']);
                    }else{
                        arr_div['bundle_show'].appendChild(arr_div['stay']);
                        arr_div['bundle_show'].appendChild(arr_div['expire']);
                        arr_div['bundle_show'].appendChild(arr_div['regular_payment']);
                        arr_div['bundle_show'].appendChild(arr_div['today_payment_yet']);
                    }
                }
            }

            // arr_div['bundle_show'] 안에 있는 하위 자식 노드의 onclick을 userPaymentMiddleTabClick(this) 추가.
            const divs = arr_div['bundle_show'].querySelectorAll('[data-div-middle]');
            divs.forEach(function(div){
                div.setAttribute('onclick', 'userPaymentMiddleTabClick(this)');
            });

            // 종류에 따라 테그 보이게.
            const bundle = document.querySelector('[data-bundle="tby_payments"]').closest('table');
            bundle.querySelectorAll('.is_due, .is_complete, .is_general, .is_leader').forEach(function(el){
                if(!is_complete && el.classList.contains('is_due')){
                    el.hidden = false;
                }
                if(is_complete && el.classList.contains('is_complete')){
                    el.hidden = false;
                }
                if(group_type2 == 'leader' && el.classList.contains('is_leader')){
                    el.hidden = false;
                }
                if(group_type2 == 'general' && el.classList.contains('is_general')){
                    el.hidden = false;
                }
            });
        }


        // 미들 카운트 가져오기.
        // 하단 결제대기,완료 학생 리스트 불러오기.
        function userPaymentBtmList(page_num){
            const teach_seq = document.querySelector('[data-select-top="teacher"]').value;
            const search_type_el = document.querySelector('[data-bundle="payment_show"] [data-div-middle].active');
            let search_type = '';
            if(search_type_el) search_type = search_type_el.getAttribute('data-div-middle');
            const team_code = document.querySelector('[data-select-top="team"]').value;
            const is_complete = document.querySelector('[data-btn-top="complete"]').classList.contains('active') ? 'Y' : 'N';

            //bundle_show의 첫 번째 div-middle를 active로 변경.
            if(search_type == "") document.querySelector('[data-bundle="payment_show"] [data-div-middle]').classList.add('active');
            const page = "/manage/user/payment/select";
            const parameter = {
                teach_seq: teach_seq,
                search_type: search_type,
                team_code: team_code,
                is_complete: is_complete,

                page_max:6,
                page:page_num
            };

            queryFetch(page, parameter, function(result){
                userPaymentCnt(result.cnt_arr);

                // 초기화
                const bundle = document.querySelector('[data-bundle="tby_payments"]');
                const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                bundle.innerHTML = '';
                bundle.appendChild(row_copy);
                // :페이징 선언.
                userPaymentTablePaging(result.payments, '1');

                const last_counsels = result.last_counsel_date_arr;
                const today = new Date();
                if(!result.payments) return;
                result.payments.data.some(function(payment){
                    const row = row_copy.cloneNode(true);
                    const is_goods = payment.goods_name ? true:false;
                    const is_goods_due = payment.goods_due_name ? true:false;
                    row.hidden = false;
                    row.setAttribute('data-row', 'clone');
                    row.querySelector('[data-payment-seq]').innerText = payment.id;
                    row.querySelector('[data-teach-name]').innerText = payment.teach_name;
                    row.querySelector('[data-group-name]').innerText = payment.group_name;
                    const rtn_student_type = userPaymentGoodsGetTypeDetail(payment, row.querySelector('[data-student-type-detail]'));
                    row.querySelector('[data-student-name]').innerText = payment.student_name;
                    row.querySelector('[data-student-seq]').value= payment.student_seq;
                    row.querySelector('[data-student-id]').innerText = payment.student_id;
                    row.querySelector('[data-school-name]').innerText = payment.school_name;
                    row.querySelector('[data-status-str]').innerText = payment.status_str;
                    row.querySelector('[data-regular-type]').innerText = payment.is_regular == 'Y' ? '정기결제':'';

                    // 대기 상태이면 숨김처리.
                    if(payment.status_no == 9){
                        row.querySelector('[data-card-name]').closest('p').hidden = true;;
                    }
                    row.querySelector('[data-card-name]').innerText = payment.card_name;
                    row.querySelector('[data-card-inst]').innerText = (payment.card_inst||'') == '' ? '일시불':payment.card_inst;
                    const last_counsel_date = last_counsels[payment.student_seq] ? last_counsels[payment.student_seq].substr(0,10).replace(/-/gi,'.'):'';
                    row.querySelector('[data-recnt-counsel-date]').innerText = last_counsel_date;
                    row.querySelector('[data-payment-due-date]').innerText = (payment.payment_due_date||'').substr(0,16).replace(/-/gi,'.').replace(' ', '\n');

                    //payment_date ? 매월 13일
                    row.querySelector('[data-payment-date]').innerText = payment.payment_date;
                    row.querySelector('[data-goods-name]').innerText = payment.goods_name;
                    row.querySelector('[data-goods-period]').innerText = is_goods ? `${payment.goods_period}개월 ${payment.is_auto_pay == 'Y' ? '월납' : '일시납'}`:'';
                    row.querySelector('[data-goods-start-date]').innerText = is_goods ? `${payment.goods_start_date} 부터`:'';
                    row.querySelector('[data-goods-end-date]').innerText = is_goods ? `${payment.goods_end_date} 까지`:'';
                    const goods_end_date = new Date(payment.goods_end_date);
                    const remain_date = Math.ceil((goods_end_date - today) / (1000 * 60 * 60 * 24));
                    row.querySelector('[data-goods-remain-date]').innerText = `(${remain_date}일)`;

                    row.querySelector('[data-regular-date]').value = payment.regular_date;
                    row.querySelector('[data-goods-seq]').value = payment.goods_seq;
                    row.querySelector('[data-is-regular]').value = payment.is_regular;

                    // 정기결제이면, 결제하기 > data-btn-payment 정기결제 예정 / > 정기결제 예정 data-is-regular-str
                    if(payment.is_regular == 'Y'){
                        row.querySelector('[data-btn-payment]').innerText = '결제상세';
                        row.querySelector('[data-is-regular-str]').innerText = '정기결제 예정';
                        row.querySelector('[data-btn-payment]').setAttribute('onclick', 'userPaymentDetailPage(this)');
                    }
                    // 만상태의 회원일 경우 / 재등록 하기
                    if(rtn_student_type == '만료'){
                        row.querySelector('[data-btn-payment]').innerText = '재등록하기';
                    }




                    bundle.appendChild(row);

                });


            });
        }

        // 미들 카운트 설정
        function userPaymentCnt(cnt_arr){
            if(!cnt_arr) return;
            const bundle_show = document.querySelector('[data-bundle="payment_show"]');
            const stay_or_com_el = bundle_show.querySelector('[data-div-middle="stay"], [data-div-middle="complete"]');
            const new_el = bundle_show.querySelector('[data-div-middle="new"]');
            const readd_el = bundle_show.querySelector('[data-div-middle="readd"]');
            const today_payment_yet_el = bundle_show.querySelector('[data-div-middle="today_payment_yet"]');
            const stay_all_cnt_el = document.querySelector('[data-payment-stay-cnt="top"]');
            const complete_all_cnt_el = document.querySelector('[data-payment-complete-cnt="top"]');

            if(stay_all_cnt_el) stay_all_cnt_el.innerText = cnt_arr.all_stay_cnt;
            if(complete_all_cnt_el) complete_all_cnt_el.innerText = cnt_arr.all_complete_cnt;

            if(stay_or_com_el) stay_or_com_el.querySelector('[data-cnt]').innerText = cnt_arr.all_cnt;
            if(new_el) new_el.querySelector('[data-cnt]').innerText = cnt_arr.new_cnt;
            if(readd_el) readd_el.querySelector('[data-cnt]').innerText = cnt_arr.readd_cnt;
            if(today_payment_yet_el) today_payment_yet_el.querySelector('[data-cnt]').innerText = cnt_arr.today_payment_cnt;
        }

        // 페이징 UI 함수

        // 페이징 버튼 클릭 함수.

        // 만든날짜 선택
        function userPaymentDateTimeSel(vthis) {
            //datetime-local format yyyy.MM.dd HH:mm 변경
            const date = new Date(vthis.value);
            vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = date.format('yyyy.MM.dd')
        }

        // 기간설정 select onchange
        function userPaymentSelectDateType(vthis, start_date_tag, end_date_tag) {
            const inp_start = document.querySelector(start_date_tag);
            const inp_end = document.querySelector(end_date_tag);

            // 0 = 기간설정 지난1주일 // end_date 에서 -7일을 start_date에 넣어준다.
            if (vthis.value == 0) {
                const end_date = new Date(inp_end.value);
                end_date.setDate(end_date.getDate() - 7);
                inp_start.value = end_date.toISOString().substr(0, 10);
            }
            // 1 = 1개월
            else if (vthis.value == 1) {
                const end_date = new Date(inp_end.value);
                end_date.setMonth(end_date.getMonth() - 1);
                inp_start.value = end_date.toISOString().substr(0, 10);
            }
            // 2 = 3개월
            else if (vthis.value == 2) {
                const end_date = new Date(inp_end.value);
                end_date.setMonth(end_date.getMonth() - 3);
                inp_start.value = end_date.toISOString().substr(0, 10);
            }
            //-1 오늘
            else if (vthis.value == -1) {
                inp_start.value = '{{ date('Y-m-d') }}';
                inp_end.value = '{{ date('Y-m-d') }}';
            }
            // onchage()
            // onchange 이벤트가 있으면 실행
            if (inp_start.oninput)
                inp_start.oninput();
            if (inp_end.oninput)
                inp_end.oninput();
        }

    // :페이징 클릭시 펑션
    function userPaymentPageFunc(target, type){
            if(type == 'next'){
                const page_next = document.querySelector(`[data-page-next="${target}"]`);
                if(page_next.getAttribute("data-is-next") == '0') return;
                // data-page 의 마지막 page_num 의 innerText를 가져온다
                const last_page = document.querySelector(`[data-page="${target}"] .page_num:last-of-type`).innerText;
                const page = parseInt(last_page) + 1;
                if(target == "1")
                     userPaymentBtmList(page);
            }
            else if(type == 'prev'){
                // [data-page-first]  next tag 의 innerText를 가져온다
                const page_first = document.querySelector(`[data-page-first="${target}"]`);
                const page = page_first.innerText;
                if(page == 1) return;
                const page_num = page*1 -1;
                if(target == "1")
                     userPaymentBtmList(page);
            }
            else{
                if(target == "1")
                     userPaymentBtmList(type);
            }
    }
    // :페이징  함수
    function userPaymentTablePaging(rData, target){
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

    // :이용권상태 신규/만료임박/만료/재등록/휴먼회원
    function userPaymentGoodsGetTypeDetail(data, tag){
        const today_date = new Date().format('yyyy-MM-dd');
        if(data.student_type == 'new'){
            if(tag){
                tag.innerText = '신규';
            }
            return '신규';
        }
        else if(data.student_type == 'readd'){
            //이용권 등록후 만료 1개월전
            if(data.goods_end_date < today_date){
                if(tag) tag.innerText = '만료';
                tag.classList.add('studyColor-bg-studyComplete');
                tag.classList.remove('basic-bg-positie');
                return '만료';
            }else if(data.goods_end_date >= today_date
            //data.goods_end_date에서 30일 뺀 날짜보다 오늘이 더 크면 만료임박
            && new Date(new Date(data.goods_end_date).getTime() - (30 * 24 * 60 * 60 * 1000)).format('yyyy-MM-dd') < today_date){
                if(tag) tag.innerText = '만료임박';
                return '만료임박';
            }
            // goods_end_date 가 오늘과 차이가 1년 이상일때
            else if(new Date(data.goods_end_date).getTime() - new Date(today_date).getTime() > (365 * 24 * 60 * 60 * 1000)){
                if(tag){
                    tag.innerText = '휴면해제';
                    tag.classList.add('scale-bg-gray_01');
                    tag.classList.add('scale-text-gray_05');
                    tag.classList.remove('basic-bg-positie');
                    tag.classList.remove('scale-text-white');

                }
                return '휴면해제';
            }
            else{
                if(tag) tag.innerText = '재등록';
                return '재등록';
            }
        }
    }

    //
    function userPaymentMiddleTabClick(vthis){
        event.stopPropagation();
        const bundle_show = document.querySelector('[data-bundle="payment_show"]');
        // 자식 노드
        const divs = bundle_show.querySelectorAll('[data-div-middle]');
        divs.forEach(function(div){
            div.classList.remove('active');
        });
        vthis.classList.add('active');

        // 리스트 불러오기.
        userPaymentBtmList();
    }
    //모달 / 결제하기 / 클리어
    function userPaymentModalClear(){
        const modal = document.getElementById('user_payment_modal');
        modal.querySelector('[data-student-name]').innerText = '';
        modal.querySelector('[data-student-type]').innerText = '';
        modal.querySelector('[data-goods-start-date]').value = new Date().format('yyyy-MM-dd');
        modal.querySelector('[data-goods-start-date]').oninput();
        modal.querySelector('[data-goods-is-auto-pay="2"]').value = 'N';
        modal.querySelector('[data-regular-date]').value = '';
        modal.querySelectorAll('[data-goods-row]').forEach(function(row){
            row.classList.remove('active');
        });
    }
    // 결제하기 버튼 클릭시 모달 오픈. / 타입에 따라 이용권변경 / 결제하기
    function userPaymentModalShow(vthis, type){
        userPaymentModalClear();
       //user_payment_modal
        const tr = vthis.closest('tr');
        const modal = document.getElementById('user_payment_modal');
        //정보 가져오기.
        const student_name = tr.querySelector('[data-student-name]').innerText;
        const student_type = tr.querySelector('[data-student-type-detail]').innerText;
        const goods_due_start_date = tr.querySelector('[data-goods-start-date]').innerText.replace(' 부터','');
        const goods_due_end_date = "";
        const is_regular = tr.querySelector('[data-is-regular]').value;
        const regular_date = tr.querySelector('[data-regular-date]').value;
        const goods_seq = tr.querySelector('[data-goods-seq]').value;

        if(type == 'change'){
            modal.querySelector('[data-title-after]').innerText = '결제 예정정보 변경';
        }else{
            modal.querySelector('[data-title-after]').innerText = '결제';
        }

        //모달의 내용 채워넣기.
        modal.querySelector('[data-student-name]').innerText = student_name;
        modal.querySelector('[data-student-type]').innerText = student_type;
        modal.querySelector('[data-goods-start-date]').value = goods_due_start_date;
        modal.querySelector('[data-goods-start-date]').oninput();
        // 정규결제인지
        modal.querySelector('[data-goods-is-auto-pay="2"]').value = is_regular == 'Y' ? 'Y':'N';
        if(regular_date) modal.querySelector('[data-regular-date]').value = regular_date;
        const goods_el = modal.querySelector(`[data-goods-row="${goods_seq}"]`)
        if(goods_el) goods_el.classList.add('active');

        // 카드정보가 있을때와 없을때 정보 보여주는 div변경.
        // 결제하기버튼, 카드 정보.
        // [추가 코드]


        const myModal = new bootstrap.Modal(document.getElementById('user_payment_modal'), {
            keyboard: false,
            backdrop: 'static'
        });
        myModal.show();
    }

    // 결제 모달에서 이용권(상품)선택시.
    function userPaymentModalGoodsSel(vthis){
        const modal = document.querySelector('#user_payment_modal');
        //data-goods-row 모두 비활성화
        modal.querySelectorAll('[data-goods-row]').forEach(function(row){
            row.classList.remove('active');
        });
        vthis.classList.add('active');
    }

    // 결제정보상세 페이지 이동.
    function userPaymentDetailPage(vthis){
        const tr = vthis.closest('tr');
        const payment_seq = tr.querySelector('[data-payment-seq]').innerText;
        const student_seq = tr.querySelector('[data-student-seq]').value;

        //data-form-payment-detail
        const form = document.querySelector('[data-form-payment-detail]');
        form.method = 'post';
        form.blank = '_self';
        form.querySelector('[name="payment_seq"]').value = payment_seq;
        form.querySelector('[name="student_seq"]').value = student_seq;

        const msg =
        `
        <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
            <p class="modal-title text-center text-sb-28px mt-28" id="">결제상세정보 페이지로 이동하시겠습니까?</p>
        </div>
        `;
        sAlert('', msg, 3, function(){
            form.submit();
        });
    }
</script>
@endsection
