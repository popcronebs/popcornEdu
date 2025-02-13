@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    선생님 정보 관리
@endsection

{{-- 컨텐트 --}}
@section('layout_coutent')
    <div class="row pt-2" data-div-main="teacher_manage">
        <input type="hidden" data-main-group-type2 value="{{ $group_type2 }}">
        <article class="sub-title d-flex justify-content-between">
            <h2 class="text-sb-42px">
                <img src="{{ asset('images/teacher_manage_icon.svg') }}" width="77">
                <span class="me-2">선생님 정보 관리</span>
            </h2>
        </article>

        <section>
            <table class="w-100 table-serach-style table-border-xless table-h-92 div-shadow-style rounded-3">
                <colgroup>
                    <col style="width: 33.33%;">
                    <col style="width: 33.33%;">
                    <col style="width: 33.33%;">
                </colgroup>
                <thead></thead>
                <tbody>
                    <tr class="text-start">
                        <td class="text-start ps-4 scale-text-gray_06">
                            <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                                <select data-select-top="region" {{ $group_type2 != 'general' ? 'disabled' : '' }}
                                    onchange="teachManGoodsSelectTop(this, 'region')"
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
                                    onchange="teachManGoodsSelectTop(this, 'team')"
                                    class="border-none lg-select rounded-0 text-sb-20px {{ $group_type2 != 'general' && $group_type2 != 'leader' ? 'scale-text-gray_05' : '' }} p-0 w-100">
                                    @if ($group_type2 == 'general')
                                        <option value="">소속 팀을 선택해주세요.</option>
                                    @else
                                        @if (!empty($team))
                                            <option value="{{ $team->team_code }}">{{ $team->team_name }}</option>
                                        @endif
                                    @endif

                                </select>
                                <img src="{{ asset('images/svg/btn_arrow_down.svg') }}"
                                    class="position-absolute end-0 bottom-0 top-0 m-auto" alt="" width="32"
                                    height="32">
                            </div>
                        </td>
                        <td class="text-start ps-4 scale-text-gray_06">
                            <label class="label-search-wrap w-100">
                                <input type="text" onkeyup="if(event.keyCode == 13) {chks = {};teachManTeacherSelect();}"
                                    class="lg-search border-none text-m-20px w-100 p-0" placeholder="선생님을 검색해주세요.">
                            </label>
                        </td>
                    </tr>
                    <tr class="text-start border-gray-bottom border-gray-top">
                        <td colspan="3" class="text-start px-4 h-92">
                            <div class="d-flex mx-0 justify-content-end align-items-center h-100 gap-2"
                                data-bundle="teacher_group_cnt">
                                <label class="checkbox row mx-0 col-auto" data-row="copy" hidden>
                                    <input type="checkbox" class="" checked>
                                    <span class="col-auto px-0"></span>
                                    <p class="text-m-20px ms-1 col-auto">운영- <span data-group-name>팀장</span>(<span
                                            data-group-cnt>200</span>)</p>
                                    <input type="hidden" data-group-seq>
                                </label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-52">
                <div class="d-flex gap-3">
                    <button type="button" onclick="teachManMovePageUsersAdd();"
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
                    <button type="button" onclick="teachManMovePageUsersAdd('/teacher/users/add/list')"
                        class="btn-line-ms-secondary text-sb-24px rounded-pill border-none scale-bg-white scale-text-white primary-bg-mian scale-text-gray_05 me-1">사용자
                        등록하기</button>
                </div>
            </div>
        </section>

        <section>
            <div class="d-flex justify-content-between align-items-end mt-120 mb-32">
                <span class="text-m-24px"><b class="studyColor-text-studyComplete">총 <span data-teachers-cnt>0</span>건</b>의
                    조회 결과가 있습니다.</span>
                <div class="h-center">
                    <label class="d-inline-block select-wrap select-icon">
                        <select id="select2"
                            onchange="teachManSelectDateType(this, '[data-search-start-date]','[data-search-end-date]');teachManTeacherSelect();"
                            class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                            <option value="">기간설정</option>
                            <option value="-1">오늘로보기</option>
                            <option value="0">1주일전</option>
                            <option value="1">1개월전</option>
                            <option value="2">3개월전</option>
                        </select>
                    </label>
                    <div class="h-center p-3 border rounded-pill">
                        <img src="{{ asset('images/calendar_gray_icon.svg') }}" class="me-2">
                        <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start"
                            style="height: 20px;">
                            <div class="h-center justify-content-between">
                                <div data-date
                                    onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                                    type="text" class="text-m-20px text-start scale-text-gray_05" readonly=""
                                    placeholder="">
                                    {{-- 상담시작일시 --}}
                                    {{ date('Y.m.d') }}
                                </div>
                                <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                            </div>
                            <input type="date" style="width: 80px;height: 0.5px;" data-search-start-date
                                oninput="teachManDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                        </div>
                        ~
                        <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start"
                            style="height: 20px;">
                            <div class="h-center justify-content-between">
                                <div data-date
                                    onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                                    type="text" class="text-m-20px text-start scale-text-gray_05" readonly=""
                                    placeholder="">
                                    {{-- 상담시작일시 --}}
                                    {{ date('Y.m.d') }}
                                </div>
                                <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                            </div>
                            <input type="date" style="width: 80px;height: 0.5px;" data-search-end-date
                                oninput="teachManDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                        </div>

                    </div>
                </div>
            </div>

            <div class="row justify-content-md-center">
                <div class="col-12 ">
                    <table class="table-style w-100" style="min-width: 100%;">
                        <colgroup>
                            <col style="width: 80px;">

                        </colgroup>
                        <thead class="">
                            <tr class="text-sb-20px modal-shadow-style rounded">
                                <th style="width: 80px">
                                    <label class="checkbox mt-1">
                                        <input type="checkbox" class="" onchange="teachManChkAll(this)">
                                        <span class="">
                                        </span>
                                    </label>
                                </th>
                                <th>직책</th>
                                <th>회원명/아이디</th>
                                <th>소속</th>
                                <th>팀</th>
                                <th>입사일 구분</th>
                                <th>퇴사일 구분</th>
                                <th>재직상태</th>
                                {{-- @if($group_type2 == 'general') <th>수정내역</th> @endif --}}
                                <th>이용 활성화</th>
                                <th>-</th>
                            </tr>
                        </thead>
                        <tbody data-bundle="tby_teachers">
                            <tr class="text-m-20px h-104" data-row="copy" hidden>
                                <input type="hidden" data-teach-seq>
                                <td class="">
                                    <label class="checkbox mt-1">
                                        <input type="checkbox" class="chk" onchange="teachManChkInput(this);">
                                        <span class="">
                                        </span>
                                    </label>
                                </td>
                                <td class="scale-text-gray_05" data-group-name data-explain="직급(그룹이름)">
                                </td>
                                <td class="scale-text-gray_05">
                                    <span data-teach-name data-explain="#선생이름"></span>(<span data-teach-id
                                        data-explain="#아이디"></span>)
                                </td>
                                <td class="s름ale-text-gray_05" data-region-name data-explain="#본부이름">
                                </td>
                                <td class="scale-text-gray_05" data-team-name data-explain="#팀이름">
                                </td>
                                <td class="scale-text-gray_05" data-join-date data-explain="#입사일"> </td>
                                <td class="scale-text-gray_05" data-resignation-date data-explain="일퇴사일"> </td>
                                <td class="scale-text-gray_05 cursor-pointer" data-teach-status data-explain="#재직유무"
                                    onclick="teachManChgTeachStusUpdate(this);">
                                </td>
                                {{-- @if($group_type2 == 'general')
                                <td class="scale-text-gray_05">
                                    <button type="button" onclick="teachManUserHistoryModal(this);"
                                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">상세보기</button>
                                </td>
                                @endif --}}
                                <td class="scale-text-gray_05">
                                    <label class="toggle">
                                        <input type="checkbox" class="" data-is-use checked=""
                                            onchange="teachMantIsUseTrEditUpdate(this)">
                                        <span class=""></span>
                                    </label>
                                </td>
                                <td class="scale-text-gray_05">
                                    <button type="button" onclick="teachManUserEditPage(this);"
                                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">{{ $group_type2 == 'general' ? '수정하기' : '상세보기' }}</button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-52">
                <div class="">

                    <button type="button"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2">
                        SMS 문자/알림톡
                    </button>
                    <button type="button"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">
                        사이트 팝업
                    </button>
                </div>
                <div class="my-custom-pagination">
                    <div class="col d-flex justify-content-center">
                        <ul class="pagination col-auto" data-ul-teach-manage-page="1" hidden>
                            <button href="javascript:void(0)" class="btn p-0 prev" data-btn-teach-manage-page-prev="1"
                                onclick="teachManPageFunc('1', 'prev')">
                                <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                            </button>
                            <li class="page-item" hidden>
                                <a class="page-link" onclick="">0</a>
                            </li>
                            <span class="page" data-span-teach-manage-page-first="1" hidden
                                onclick="teachManPageFunc('1', this.innerText);" disabled>0</span>
                            <button href="javascript:void(0)" class="btn p-0 next" data-btn-teach-manage-page-next="1"
                                onclick="teachManPageFunc('1', 'next')" data-is-next="0">
                                <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                            </button>
                        </ul>
                    </div>
                </div>
                <div>
                    <button type="button" onclick="teachManExcelDownload();"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="me-1">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10.8649 16.6265C10.2489 16.6265 9.74959 16.1271 9.74959 15.5111V10.8574L6.87216 10.8574C5.84408 10.8574 5.33501 9.60924 6.06985 8.89023L11.1707 3.89928C11.6166 3.46299 12.3294 3.46299 12.7753 3.89928L17.8762 8.89024C18.611 9.60924 18.1019 10.8574 17.0739 10.8574L14.166 10.8574V15.5111C14.166 16.1271 13.6667 16.6265 13.0507 16.6265H10.8649Z"
                                fill="#DCDCDC"></path>
                            <rect x="5.57031" y="17.8208" width="12.8027" height="1.75074" rx="0.875369"
                                fill="#DCDCDC"></rect>
                        </svg>
                        엑셀로 내보내기
                    </button>
                </div>
            </div>
        </section>

        @if($group_type2 == 'general')
        <section>
            <div class="scale-bg-gray_01 p-4 rounded-3 d-flex justify-content-between mt-52">
                <div>
                    <button type="button"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded h-52 scale-text-gray_05 px-3 me-2 align-bottom"
                        disabled="" data-region-name-btm>
                        @if ($group_type2 == 'general')
                            소속본부미지정
                        @else
                            {{ $region->region_name }}
                        @endif
                    </button>
                    <input type="hidden" data-region-seq-btm>
                    <div class="d-inline-block select-wrap select-icon scale-bg-white">
                        <select data-select-btm="team" class="border-gray sm-select text-sb-20px h-52">
                            <option value="">미배정</option>
                        </select>
                    </div>

                </div>
                <div>
                    <button type="button" onclick="teachManChgRegionTeam();"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded h-52 border-dark scale-text-black scale-bg-white px-3 me-2 align-bottom">
                        선택 회원 소속/관할 변경
                    </button>
                </div>
            </div>
        </section>
        @endif
        {{-- 160px --}}
        <div>
            <div class="py-lg-5"></div>
            <div class="py-lg-4"></div>
            <div class="pt-lg-3"></div>
        </div>

        {{-- 모달 / 수정 내역 리스트 --}}
        <div class="modal fade" id="teach_man_modal_edit_history" tabindex="-1" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            <span class="spinner-border spinner-border-sm sp_loding" aria-hidden="true" hidden></span>
                            수정 내역 리스트
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick=""></button>
                    </div>
                    <div class="modal-body">
                        <div class="overflow-auto tableFixedHead" style="height: auto; max-height: 400px;">
                            <table class="table">
                                {{-- 굳이 id할 필요 없을듯 modal 안에 .으로 가져오기. --}}
                                <tbody class="tby_edit_history">
                                    <tr class="copy_tr_edit_history" hidden>
                                        <td class="border-bottom-0">
                                            <div class="log_content"></div>
                                            <div class="created_at fs-7"></div>
                                            <div>
                                                <a class="log_remark" href="javascript:void(0)"
                                                    onclick="userlistLogRemarkEdit(this);"></a>
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
        {{-- 모달 / 선생님 / 선택 회원 소속/관할 변경. --}}
        <div class="modal fade" id="teach_man_modal_region_team_change" tabindex="-1" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog  modal-dialog-centered rounded-4">
                <input type="hidden" class="teach_seqs">
                <input type="hidden" class="chg_region_seq">
                <input type="hidden" class="chg_team_code">
                <div class="modal-content">
                    <div class="modal-header" hidden>
                        <h1 class="modal-title fs-5">소속/관할 변경</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick="userlistModalRegionTeamChangeClear();"></button>
                    </div>
                    <div class="modal-body">

                        {{-- 소속을 변경하시겠습니까? --}}

                        <p class="text-m-20px all-center alert-top-m-20 mb-4">
                            <span class="region_name"></span>
                            <span class="team_name ms-2"></span>
                            <svg class="mx-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <g clip-path="url(#clip0_552_30792)">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M19.2742 12.3297C19.3364 12.1813 19.3707 12.0183 19.3707 11.8474C19.3707 11.4888 19.2196 11.1654 18.9777 10.9375L13.0489 5.00869C12.5608 4.52054 11.7693 4.52054 11.2812 5.00869C10.793 5.49685 10.793 6.28831 11.2812 6.77646L15.1022 10.5975L6.18775 10.5976C5.4974 10.5976 4.93776 11.1572 4.93777 11.8476C4.93778 12.5379 5.49743 13.0976 6.18778 13.0976L15.1032 13.0975L10.9635 17.2371C10.4753 17.7253 10.4753 18.5168 10.9635 19.0049C11.4516 19.4931 12.2431 19.4931 12.7313 19.0049L19.0043 12.7319C19.1233 12.6129 19.2133 12.4759 19.2742 12.3297Z" fill="#222222"></path>
                              </g>
                              <defs>
                              <clipPath id="clip0_552_30792">
                              <rect x="24" width="24" height="24" rx="12" transform="rotate(90 24 0)" fill="white"></rect>
                              </clipPath>
                              </defs>
                            </svg>
                            <span class="chg_region_name"></span>
                            <span class="chg_team_name ms-2"></span>
                          </p>

                          <p class="modal-title text-center text-b-28px " id="">선택하신 선생님의</p>
                          <p class="modal-title text-center text-b-28px alert-bottom-m studyColor-text-studyComplete" id="">팀을 변경하시겠습니까?</p>

                        <p class="modal-title text-center text-b-20px scale-text-gray_05">(담당학생은 <b class="scale-text-black">담당선생님(미배정)</b>으로 변경됩니다.)</p>

                        <div class="overflow-auto mt-32" style="height: auto; max-height: 300px;">
                            <table class="table border-white">
                                <tbody id="teach_man_tby_student_cnt">
                                    <tr class="copy_tr_student_cnt">
                                        <td class="">
                                            <div class="d-flex justify-content-between align-items-center scale-bg-gray_01 rounded px-4 h-68 w-100">
                                                <p class="text-m-20px scale-text-black teach_name" data="#이름">최담당</p>
                                                <p class="text-m-20px scale-text-gray_05 student_cnt">담당학생 20명</p>
                                              </div>
                                        </td>
                                        <input type="hidden" class="teach_seq">
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="row w-100 p-0 m-0">
                            <div class="col-6 ps-0">
                              <button type="button" onclick="teachManModalRegionTeamChangeClear()"
                              class="btn-lg-secondary text-sb-24px rounded scale-bg-gray_01 scale-text-gray_05 w-100 justify-content-center" data-bs-dismiss="modal">닫기</button>
                            </div>
                            <div class="col-6 pe-0">
                              <button type="button" onclick="teachManChgRegionTeamInsert()"
                              class="btn-lg-primary text-b-24px rounded scale-text-white w-100 text-center justify-content-center">
                                <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                              팀 변경하기
                            </button>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <form action="/teacher/users/add/excel" data-form-user-add-excel hidden>
        @csrf
        <input name="user_type">
        <input name="region_seq">
        <input name="team_code">
    </form>
    <form action="/teacher/detail" data-form-teacher-info-detail hidden>
        @csrf
        <input name="teach_seq">
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(document.querySelector('[data-main-group-type2]').value == 'leader')
                teachManTeacherGroupCntSelect()
        });
        document.addEventListener('visibilitychange', function(event) {
            if (sessionStorage.getItem('isBackNavigation') === 'true') {
                console.log('뒤로 가기 버튼을 클릭한 후 페이지가 로드되었습니다.');
                // 여기에 뒤로 가기 버튼을 클릭한 후 페이지가 로드되었을 때 실행할 코드를 작성합니다.
                sessionStorage.removeItem('isBackNavigation'); // 상태를 초기화합니다.

                teachManTeacherSelect();
            }
        });
        // 상단 select_tag(el) 선택시
        function teachManGoodsSelectTop(vthis, type) {
            if (type == 'region' || type == 'region_modal') {
                const region_seq = vthis.value;
                teachManGoodsTeamSelect(region_seq);
                teachManTeacherGroupCntSelect();
                document.querySelector('[data-region-name-btm]').innerHTML = vthis.selectedOptions[0].innerText;
                document.querySelector('[data-region-seq-btm]').value = region_seq;
                teachManGoodsTeamSelect(region_seq, true);
            } else if (type == 'team' || type == 'team_modal') {
                teachManTeacherGroupCntSelect();
            } else if (type == 'teacher') {

            }
        }

        // 본부 선택시 팀 SELECT
        function teachManGoodsTeamSelect(region_seq, is_btm) {
            const page = '/manage/useradd/team/select';
            const parameter = {
                region_seq: region_seq
            };
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    let select_team = document.querySelector('[data-select-top="team"]');
                    if (is_btm) select_team = document.querySelector('[data-select-btm="team"]');
                    select_team.innerHTML = '';
                    const option = document.createElement('option');
                    option.value = '';
                    option.innerText = '소속 팀을 선택해주세요.';
                    if (is_btm) option.innerText = '미배정';
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
        function teachManTeacherGroupCntSelect() {
            const region_seq = document.querySelector('[data-select-top="region"]').value;
            const team_code = document.querySelector('[data-select-top="team"]').value;

            // 선생님 그룹별 카운트 가져오기
            const page = '/teacher/manage/group/count/select';
            const parameter = {
                region_seq: region_seq,
                team_code: team_code
            };
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    const bunlde = document.querySelector('[data-bundle="teacher_group_cnt"]');
                    const copy_row = bunlde.querySelector('[data-row="copy"]').cloneNode(true);
                    bunlde.innerHTML = '';
                    bunlde.appendChild(copy_row);
                    result.group_counts.forEach(function(group_count) {
                        const row = copy_row.cloneNode(true);
                        row.setAttribute('data-row', 'clone');
                        row.hidden = false;
                        row.querySelector('[data-group-name]').innerText = group_count.group_name;
                        row.querySelector('[data-group-cnt]').innerText = group_count.cnt;
                        row.querySelector('[data-group-seq]').value = group_count.group_seq;
                        bunlde.appendChild(row);
                    });
                } else {

                }
            });
        }

        // 선생님 목록 불러오기.
        function teachManTeacherSelect(page_num) {
            // 체크된 group-seq 가져오기
            const group_seqs = [];
            const bundle = document.querySelector('[data-bundle="teacher_group_cnt"]');
            const group_rows = bundle.querySelectorAll('[data-row="clone"]');
            group_rows.forEach(function(group_row) {
                const group_seq = group_row.querySelector('[data-group-seq]').value;
                const checked = group_row.querySelector('input[type="checkbox"]').checked;
                if (checked) {
                    group_seqs.push(group_seq);
                }
            });

            const group_type = 'teacher';
            const search_type = 'id';
            const search_region = document.querySelector('[data-select-top="region"]').value;
            const search_team = document.querySelector('[data-select-top="team"]').value;
            if (search_region == '') {
                toast('본부를 선택해주세요.');
                return;
            }
            const page = '/manage/userlist/teacher/select';
            const parameter = {
                group_seq: group_seqs,
                group_type: group_type,
                search_type: search_type,
                search_region: search_region,
                search_team: search_team,
                page: page_num || 1,
                page_max: 6
            };
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    const bundle = document.querySelector('[data-bundle="tby_teachers"]');
                    const copy_row = bundle.querySelector('[data-row="copy"]');
                    bundle.innerHTML = '';
                    bundle.appendChild(copy_row);

                    const teachers = result.resultData;
                    teachManTablePaging(teachers, "1");
                    const teachers_cnt = document.querySelector('[data-teachers-cnt]');
                    teachers_cnt.innerText = teachers.total;
                    teachers.data.forEach(function(teacher) {
                        const row = copy_row.cloneNode(true);
                        row.hidden = false;
                        row.setAttribute('data-row', 'clone');
                        row.querySelector('[data-teach-seq]').value = teacher.id;
                        row.querySelector('[data-group-name]').innerText = teacher.group_name;
                        row.querySelector('[data-teach-name]').innerText = teacher.teach_name;
                        row.querySelector('[data-teach-id]').innerText = teacher.teach_id;
                        row.querySelector('[data-region-name]').innerText = teacher.region_name;
                        row.querySelector('[data-team-name]').innerText = teacher.team_name||'미배정';
                        row.querySelector('[data-join-date]').innerText = (teacher.join_date || teacher
                            .created_at || '').substr(2, 8).replace(/-/g, '.');
                        row.querySelector('[data-resignation-date]').innerText = (teacher.resignation_date || '')
                            .substr(2, 8).replace(/-/g, '.');
                        row.querySelector('[data-teach-status]').innerText = teacher.teach_status == 'Y' ?
                            '재직' : '퇴사(계약)';
                        row.querySelector('[data-teach-status]').setAttribute('data-text', teacher
                            .teach_status);
                        row.querySelector('[data-is-use]').checked = teacher.is_use == 'Y' ? true : false;
                        bundle.appendChild(row);
                        if(chks[teacher.id]) {
                            row.querySelector('.chk').checked = true;
                        }
                    });

                } else {

                }
            });
        }
        // 기간설정 select onchange
        function teachManSelectDateType(vthis, start_date_tag, end_date_tag) {
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
        // 만든날짜 선택
        function teachManDateTimeSel(vthis) {
            //datetime-local format yyyy.MM.dd HH:mm 변경
            const date = new Date(vthis.value);
            vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = date.format('yyyy.MM.dd')
        }
        // 페이징 클릭시 펑션
        function teachManPageFunc(target, type) {
            if (type == 'next') {
                const page_next = document.querySelector(`[data-btn-teach-manage-page-next="${target}"]`);
                if (page_next.getAttribute("data-is-next") == '0') return;
                // data-ul-teach-manage-page 의 마지막 page_num 의 innerText를 가져온다
                const last_page = document.querySelector(`[data-ul-teach-manage-page="${target}"] .page_num:last-of-type`)
                    .innerText;
                const page = parseInt(last_page) + 1;
                if (target == "1")
                    teachManTeacherSelect(page);
            } else if (type == 'prev') {
                // [data-span-teach-manage-page-first]  next tag 의 innerText를 가져온다
                const page_first = document.querySelector(`[data-span-teach-manage-page-first="${target}"]`);
                const page = page_first.innerText;
                if (page == 1) return;
                const page_num = page * 1 - 1;
                if (target == "1")
                    teachManTeacherSelect(page);
            } else {
                if (target == "1")
                    teachManTeacherSelect(type);
            }
        }

        function teachManTablePaging(rData, target) {
            const from = rData.from;
            const last_page = rData.last_page;
            const per_page = rData.per_page;
            const total = rData.total;
            const to = rData.to;
            const current_page = rData.current_page;
            const data = rData.data;
            //페이징 처리
            const notice_ul_page = document.querySelector(`[data-ul-teach-manage-page='${target}']`);
            //prev button, next_button
            const page_prev = notice_ul_page.querySelector(`[data-btn-teach-manage-page-prev='${target}']`);
            const page_next = notice_ul_page.querySelector(`[data-btn-teach-manage-page-next='${target}']`);
            //페이징 처리를 위해 기존 페이지 삭제
            notice_ul_page.querySelectorAll(".page_num").forEach(element => {
                element.remove();
            });
            //#page_first 클론
            const page_first = document.querySelector(`[data-span-teach-manage-page-first='${target}']`);
            //페이지는 1~10개 까지만 보여준다.
            let page_start = 1;
            let page_end = 10;
            if (current_page > 5) {
                page_start = current_page - 4;
                page_end = current_page + 5;
            }
            if (page_end > last_page) {
                page_end = last_page;
                if (page_end <= 10)
                    page_start = 1;
            }


            let is_next = false;
            for (let i = page_start; i <= page_end; i++) {
                const copy_page_first = page_first.cloneNode(true);
                copy_page_first.innerText = i;
                copy_page_first.removeAttribute("data-span-teach-manage-page-first");
                copy_page_first.classList.add("page_num");
                copy_page_first.hidden = false;
                //현재 페이지면 active
                if (i == current_page) {
                    copy_page_first.classList.add("active");
                }
                //#page_first 뒤에 붙인다.
                notice_ul_page.insertBefore(copy_page_first, page_next);
                //시작 페이지 보다 11보다 큰 i 이면 page_prev disabled 을 제거
                if (i > 11) {
                    page_next.setAttribute("data-is-next", "1");
                    page_prev.classList.remove("disabled");
                } else {
                    page_next.setAttribute("data-is-next", "0");
                }
                if (i == 1) {
                    // page_prev.classList.add("disabled");
                }
                if (last_page == i) {
                    // page_next.classList.add("disabled");
                    is_next = true;
                }
            }
            if (!is_next) {
                page_next.classList.remove("disabled");
            }

            if (data.length != 0)
                notice_ul_page.hidden = false;
        }

        // 선생님 재직 상태 변경.
        function teachManChgTeachStusUpdate(vthis) {
            const tr = vthis.closest('tr');
            const teach_seq = tr.querySelector('[data-teach-seq]').value;
            let teach_status = vthis.getAttribute('data-text');
            const resignation_date = vthis.getAttribute('data-resignation-date');
            //반대로
            teach_status = teach_status == 'Y' ? 'N' : 'Y';

            //선택된 선생님 재직상태 변경
            const page = "/manage/userlist/teacher/status/update";
            const parameter = {
                teach_seq: teach_seq,
                teach_status: teach_status,
                resignation_date: resignation_date
            };
            const status_str = (teach_status == 'Y' ? '재직' : '퇴사(계약)');
            const msg =
                `
            <div class="text-center text-sb-28px"><span class="text-danger">${status_str}</span>(으)로 변경하시겠습니까?</div>
        `;
            sAlert('', msg, 3, function() {
                queryFetch(page, parameter, function(result) {
                    if (result.resultCode == 'success') {
                        toast('변경되었습니다.');
                        vthis.setAttribute('data-text', teach_status);
                        vthis.innerText = status_str;
                    }
                });
            });
        }

        // 선생님 이용 활성화 변경
        function teachMantIsUseTrEditUpdate(vthis) {
            const tr = vthis.closest('tr');
            const teach_seq = tr.querySelector('[data-teach-seq]').value;
            const is_use = vthis.checked ? 'Y' : 'N';

            const page = '/manage/userlist/user/use/update';
            const parameter = {
                user_key: teach_seq,
                group_type: 'teacher',
                chk_val: is_use,
            };
            queryFetch(page, parameter, function(result) {
                if (result.resultCode == '1' || result.resultCode == 'success') {
                    toast('변경되었습니다.');
                }
            });
        }


        // 선택 회원 소속/관할 변경 버튼 클릭
        function teachManChgRegionTeam() {
            teachManModalRegionTeamChangeClear();
            //먼저 소속을 선택해주세요.
            const modal = document.querySelector('#teach_man_modal_region_team_change');
            const sel_region = document.querySelector('[data-region-seq-btm]');
            const sel_team = document.querySelector('[data-select-btm="team"]');

            if(sel_region.value.length < 1){
                toast('먼저 소속을 선택해주세요.');
                return;
            }
            //선택된 회원
            const chkd = document.querySelectorAll('[data-bundle=tby_teachers] [data-row="clone"] .chk:checked');

            //0명 은 리턴
            if(chkd.length < 1){
                toast('선택된 회원이 없습니다.');
                return;
            }

            let teach_seqs = '';
            //선택된 회원의 user_key 가져오기
            chkd.forEach(function(el){
                const tr = el.closest('tr');
                const teach_seq = tr.querySelector('[data-teach-seq]').value;
                if(teach_seqs != '')
                    teach_seqs += ',';
                teach_seqs += teach_seq;
            });

            //담당학생 수치 가져오기.
            teachManTeachChargeStCntSelect(teach_seqs);

            //선택이 1명이면 그 선생님의 소속/관할을 modal에서 보여준다.
            // if(chkd.length == 1){
                const teach_region_name = chkd[0].closest('tr').querySelector('[data-region-name]').innerHTML;
                const teach_team_name = chkd[0].closest('tr').querySelector('[data-team-name]').innerHTML;
                modal.querySelector('.region_name').innerHTML = teach_region_name;
                modal.querySelector('.team_name').innerHTML = teach_team_name;
            // }
            const chg_region_name = document.querySelector('[data-region-name-btm]').innerText;
            const chg_team_name = sel_team.options[sel_team.selectedIndex].text;
            modal.querySelector('.chg_region_name').innerHTML = chg_region_name;
            modal.querySelector('.chg_team_name').innerHTML = chg_team_name;

            //modal에 선택된 teach_seqs 넣기. / 변경할 region_seq, team_code 넣기.
            modal.querySelector('.teach_seqs').value = teach_seqs;
            modal.querySelector('.chg_region_seq').value = sel_region.value;
            modal.querySelector('.chg_team_code').value = sel_team.value;


            //모달 열기
            const myModal = new bootstrap.Modal(document.getElementById('teach_man_modal_region_team_change'), {});
            myModal.show()
        }
        // 선택 회원 소속/ 관할 모달 비우기.
        function teachManModalRegionTeamChangeClear() {
            const modal = document.querySelector('#teach_man_modal_region_team_change');
            modal.querySelector('.chg_region_name').innerHTML = '';
            modal.querySelector('.chg_team_name').innerHTML = '';
            modal.querySelector('.region_name').innerHTML = '';
            modal.querySelector('.team_name').innerHTML = '';
            modal.querySelector('.teach_seqs').value = '';
            modal.querySelector('.chg_region_seq').value = '';
            modal.querySelector('.chg_team_code').value = '';

            //초기화
            const tby_student_cnt = modal.querySelector('#teach_man_tby_student_cnt');
            const copy_tr = tby_student_cnt.querySelector('.copy_tr_student_cnt').cloneNode(true);
            tby_student_cnt.innerHTML = '';
            tby_student_cnt.appendChild(copy_tr);
            copy_tr.hidden = true;
        }

        // 담당학생 수치 가져오기.
        function teachManTeachChargeStCntSelect(teach_seqs){
            const page = "/manage/userlist/teacher/charge/stcnt/select";
            const parameter = {
                teach_seqs:teach_seqs
            };
            const modal = document.querySelector('#teach_man_modal_region_team_change');
            //로딩 시작
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    //로딩 끝
                    //초기화
                    const tby_student_cnt = modal.querySelector('#teach_man_tby_student_cnt');
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
        function teachManChgRegionTeamInsert(){
            const modal = document.querySelector('#teach_man_modal_region_team_change');
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
                    toast('저장되었습니다.');
                    //모달 닫기
                    modal.querySelector('.btn-close').click();
                    //리스트 다시 가져오기 .page_num.active innerText 가져오기
                    const page_num = document.querySelector('.page_num.active').innerText;
                    teachManTeacherSelect(page_num);
                }
            });
        }

        //엑셀불러오기 페이지로 이동.
        function teachManMovePageUsersAdd(url){
            //data-form-user-add-excel 에 user_type, region_seq, team_code 넣기.
            const group_type2 = document.querySelector('[data-main-group-type2]').value;
            const form = document.querySelector('[data-form-user-add-excel]');
            const user_type = 'teacher';
            const region_seq = document.querySelector('[data-select-top="region"]').value;
            const team_code = document.querySelector('[data-select-top="team"]').value;

            form.querySelector('[name="user_type"]').value = user_type
            form.querySelector('[name="region_seq"]').value = region_seq;
            form.querySelector('[name="team_code"]').value = team_code;
            if(url) form.action = url;
            else form.action = '/teacher/users/add/excel';

            if(region_seq == ''){
                toast('상단에서 본부를 선택해주세요.');
                return;
            }
            if(group_type2 == 'leader'){
                if(team_code == ''){
                    toast('상단에서 팀을 선택해주세요.');
                    return;
                }
            }

            form.method = 'post';
            form.target = '_self';
            form.submit();
        }

        function teachManUserEditPage(vthis){
            const tr = vthis.closest('tr');
            const teach_seq = tr.querySelector('[data-teach-seq]').value;
            //form 을 만들어서 teach_seq 넣고 submit
            const form = document.querySelector('[data-form-teacher-info-detail]');
            form.querySelector('[name="teach_seq"]').value = teach_seq;
            form.method = 'post';
            form.target = '_self';
            form.submit();
        }
        let chks = {};
        // 체크박스 체크
        function teachManChkInput(vthis){
            const tr = vthis.closest('tr');
            const teach_seq = tr.querySelector('[data-teach-seq]').value;
            if(vthis.checked){
                chks[teach_seq] = {
                    teach_seq:teach_seq,
                    outer_html:tr.outerHTML
                };
            }else{
                delete chks[teach_seq];
            }
        }
        function teachManChkAll(vthis){
            const chks = document.querySelectorAll('.chk');
            chks.forEach(function(chk){
                chk.checked = vthis.checked;
                teachManChkInput(chk);
            });
        }

        // 엑셀로 내보내기
        function teachManExcelDownload(){
            // 체크가 있는지 확인
            const keys = Object.keys(chks);
            if(keys.length < 1){
                toast('선택된 회원이 없습니다.');
                return;
            }

            const table = document.querySelector('[data-bundle="tby_teachers"]').closest('table').cloneNode(true);
            const bundle = table.querySelector('[data-bundle="tby_teachers"]');
            bundle.innerHTML = '';

            keys.forEach(function(key){
                bundle.innerHTML += chks[key].outer_html;
            });

            const html = table.outerHTML;
            _excelDown('학생목록.xls', '학생목록', html);
        }
    </script>
@endsection
