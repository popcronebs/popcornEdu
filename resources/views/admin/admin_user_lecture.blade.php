@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    사용자 별 수강 관리
@endsection

{{-- 추가 코드 
    .페이징 진행.
    .수강완료, 관심강좌, 재수강 리스트
--}}
@section('layout_coutent')
<style>
.table-style.p34 thead tr th, 
.table-style.p34 tr td{
    padding:34px;
}
.btn-line-ss-primary:hover,
.btn-line-ss-primary:focus{
    background: #f9f9f9;
}
.slide-in {
    animation: slideIn 0.5s forwards;
  }
  @keyframes slideIn {
    from {
      transform: translateY(-100%);
    }
    to {
      transform: translateY(0);
    }
  }
  .slide-out{
    animation: slideOut 0.5s forwards;
  }
    @keyframes slideOut {
        from {
        transform: translateY(0);
        }
        to {
        transform: translateY(-100%);
        }
    }
</style>
    <div class="col pe-3 ps-3 mb-3 pt-5 row position-relative">
        {{-- 학생 수강관리 시작 --}}
        <article class="pt-5 mt-4 px-0">
            <section>
                <div class="row">
                    <div class="col-lg">
                        <div class="h-center">
                            <img src="{{ asset('images/graphic_student_info_management.svg') }}" width="72">
                            <span class="cfs-1 fw-semibold align-middle">학생 수강관리</span>
                        </div>
                    </div>
                </div>
            </section>
        </article>
        {{-- 80px --}}
        <div>
            <div class="py-lg-4"></div>
            <div class="py-lg-3"></div>
        </div>
        {{-- 상단 회원 검색 --}}
        <div class="row mx-0 px-0">
            <div class="row p-0 mx-0 modal-shadow-style py-4 px-5 rounded-4">
                <div class="row col gap-2 align-items-center justify-content-between py-2 px-1">
                    {{-- region --}}
                    <div class="col-auto d-inline-block  {{ $is_one_region ? '':'select-icon select-wrap' }} me-1 px-0" style="min-width:200px">
                        <select class="rounded-pill {{ $is_one_region ? 'scale-bg-gray_01 border-0':'border-gray' }}  lg-select text-sb-24px ps-4" id="userlect_sel_region" onchange="userlectTeamSelect(this)"
                            style="min-width:200px;padding-top:18px;padding-bottom:18px;" {{ $is_one_region ? 'disabled':'' }}>
                            @if(count($regions) != 1)
                                <option value="">소속</option>
                            @endif
                            @if(!empty($regions))
                                @foreach($regions as $region)
                                    <option value="{{$region->id}}">{{$region->region_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    {{-- team --}}
                    <div class="col-auto d-inline-block select-wrap select-icon px-0" style="min-width:200px">
                        <select class="rounded-pill border-gray lg-select text-sb-24px ps-4" id="userlect_sel_team"
                            style="min-width:200px;padding-top:18px;padding-bottom:18px;">
                            <option value="">팀</option>
                        </select>
                    </div>
                    <div class="col text-end d-flex justify-content-end gap-2">
                        {{-- name, id, 전화번호 --}}
                        <div class="col-auto d-inline-block select-wrap select-icon px-0 me-1" style="min-width:200px">
                            <select class="rounded-pill border-gray lg-select text-sb-24px ps-4" id="userlect_sel_search_type"
                                style="min-width:200px;padding-top:18px;padding-bottom:18px;">
                                <option value="student_name">이름</option>
                            <option value="student_phone">전화번호</option>
                            <option value="parent_name">학부모</option>
                            <option value="parent_phone">학부모 전화번호</option>
                            </select>
                        </div>
                        {{-- input serach str --}}
                        <div class="col-auto">
                            <label class="label-search-wrap">
                                <input id="userlect_input_search_str" 
                                type="text" class="lg-search border-gray rounded-pill text-m-20px" placeholder="찾으실 단어를 검색해주세요." 
                                onkeyup="if(event.keyCode == 13){userlectUserSelect();}">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 회원 목록 --}}
            <div class="row p-0 tableFixedHead overflow-auto mx-0 mt-3" style="max-height:260px;">
                <table class="w-100 table-style p34 table-h-82">
                    <thead class="bg-white">
                        <tr class="text-sb-20px">
                            <th>학교/학년</th>
                            <th>회원명/아이디</th>
                            <th>휴대전화</th>
                            <th>최근 결제일자</th>
                            <th>학부모</th>
                            <th>학부모 연락처</th>
                        </tr>
                    </thead>
                    <tbody id="userlect_tby_user">
                        <tr class="copy_tr_user text-m-20px scale-bg-gray_01-hover" hidden onclick="userlectUserTrClick(this)">
                            <td data="#학교/학년" class="scale-text-black">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                                <span class="school_name"></span>
                                <span class="grade"></span>
                            </td>
                            <td data="#회원명/아이디" class="scale-text-black">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                                <span class="student_name"></span>
                                <span class="student_id"></span>
                            </td>
                            <td data="#휴대전화">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                                <span class="student_phone"></span>
                            </td>
                            <td data="#최근 결제일자" >
                                <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                                <span class="payment_last_date scale-text-gray_05"></span>
                            </td>
                            <td data="#학부모" >
                                <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                                <span class="parent_name scale-text-gray_05"></span>
                            </td>
                            <td data="#학부모 연락처">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                                <span class="parent_phone scale-text-gray_05"></span>
                            </td>
                            <input type="hidden" class="student_seq">
                            <input type="hidden" class="region_name">
                            <input type="hidden" class="team_name">
                        </tr>
                    </tbody>
                </table>
                {{-- 회원 목록이 없습니다. --}}
                <div class="col-12 text-center p-3" id="userlect_div_user_empty" hidden>
                    <span>회원 목록이 없습니다.</span>
                </div>
            </div>
            {{-- 선택시 소속 정보 가져오기. --}}
            <div class="row p-0 mt-2 border" hidden>
                <div class="col p-3">
                    <span id="userlect_span_region_name"></span>
                    <span id="userlect_span_team_name"></span>
                </div>
                <div class="col p-3">
                    <span id="userlect_span_student_name"></span>
                    <span id="userlect_span_school_name"></span>
                </div>
            </div>
        </div>

        {{-- 120px --}}
        <div>
            <div class="py-lg-5"></div>
            <div class="pt-lg-4"></div>
        </div>

        {{-- 중간 탭 --}}
        {{-- 수강중인 강좌, 수강완료 강좌, 수강중인 강의, 수강완료 강의, 관심 강좌, 미수강 / 재수강 예정 --}}
        <section class="row row-cols-lg-4 gx-4">
            <div class="">
                <div data-div-userlect-course-status-main-tab="1" onclick="userlectCourseStatusMainTab(this);" class="rounded-3 scale-bg-gray_01 primary-bg-mian-hover cursor-pointer h-100 p-4">
                    <div class="row mx-0">
                        <div class="col">
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover">
                                <span data-student-name>김팝콘</span> 
                                학생의</div>
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover pt-2">
                                <span class="text-b-24px scale-text-white-hover">수강완료 강좌</span> 입니다.
                            </div>
                        </div>
                        <div class="col-auto">
                            <span  data-span-userlect-complete-cnt
                            class="text-b-42px scale-text-white-hover">0</span>
                            <span class="text-b-20px scale-text-gray_05 primary-text-light-hover">강</span>
                        </div>

                    </div>
                    
                    <div class="pt-5 mt-2">
                        <span class="text-sb-18px scale-text-gray_05 primary-text-light-hover">신청한 강좌 중 완료된 강좌
                            개수</span>
                    </div>
                </div>
            </div>
            <div class="">
                <div data-div-userlect-course-status-main-tab="2" onclick="userlectCourseStatusMainTab(this);" class="rounded-3 scale-bg-gray_01 primary-bg-mian-hover cursor-pointer h-100 p-4 ">
                    <div class="row mx-0">
                        <div class="col">
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover">
                                <span data-student-name>김팝콘</span> 
                                학생의</div>
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover pt-2">
                                <span class="text-b-24px scale-text-white-hover">관심강좌</span> 입니다.
                            </div>
                        </div>
                        <div class="col-auto">
                            <span data-span-userlect-like-cnt 
                            class="text-b-42px scale-text-white-hover">0</span>
                            <span class="text-b-20px scale-text-gray_05 primary-text-light-hover">강</span>
                        </div>

                    </div>
                    
                    <div class="pt-5 mt-2">
                        <span class="text-sb-18px scale-text-gray_05 primary-text-light-hover">관심 강좌로 지정한 강의 개수</span>
                    </div>
                </div>
            </div>
            <div class="">
                <div data-div-userlect-course-status-main-tab="3" onclick="userlectCourseStatusMainTab(this);" class="rounded-3 scale-bg-gray_01 primary-bg-mian-hover cursor-pointer h-100 p-4 ">
                    <div class="row mx-0">
                        <div class="col">
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover">
                                <span data-student-name>김팝콘</span> 
                                학생의</div>
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover pt-2">
                                <span class="text-b-24px scale-text-white-hover">미수강</span> 입니다.
                            </div>
                        </div>
                        <div class="col-auto">
                            <span data-span-userlect-ready-cnt 
                            class="text-b-42px scale-text-white-hover">0</span>
                            <span class="text-b-20px scale-text-gray_05 primary-text-light-hover">강</span>
                        </div>

                    </div>
                    
                    <div class="pt-5 mt-2">
                        <span class="text-sb-18px scale-text-gray_05 primary-text-light-hover">수강중인 강의 중 기간안에 미수강 강의 개수</span>
                    </div>
                </div>
            </div>
            <div class="">
                <div data-div-userlect-course-status-main-tab="4" onclick="userlectCourseStatusMainTab(this);" class="rounded-3 scale-bg-gray_01 primary-bg-mian-hover cursor-pointer h-100 p-4 ">
                    <div class="row mx-0">
                        <div class="col">
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover">
                                <span data-student-name>김팝콘</span> 
                                학생의</div>
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover pt-2">
                                <span class="text-b-24px scale-text-white-hover">재수강</span> 입니다.
                            </div>
                        </div>
                        <div class="col-auto">
                            <span data-span-userlect-again-cnt 
                            class="text-b-42px scale-text-white-hover">0</span>
                            <span class="text-b-20px scale-text-gray_05 primary-text-light-hover">강</span>
                        </div>

                    </div>
                    
                    <div class="pt-5 mt-2">
                        <span class="text-sb-18px scale-text-gray_05 primary-text-light-hover">완료 된 강의 중 다시 들으려고 담아놓은 강의 개수</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- 90px --}}
        <div>
            <div class="py-lg-4"></div>
            <div class="py-lg-3"></div>
            <div style="height:10px;"></div>
        </div>

        {{-- 수강완료 강좌--}}
        <section data-section-userlect-course-status="1" hidden>
            <div class="row mx-0">
                <div class="col row align-items-center">
                    <span class="col-auto  text-sb-20px bg-danger rounded-pill text-white py-2 px-3">6강좌 -
                        72강의</span>
                    <span class="col-auto  text-sb-24px scale-text-gray_05 px-1"> 가 있습니다.</span>
                </div>
                <div class="col-auto row mx-0 p-0 gap-2">
                    <div class="col-auto d-inline-block select-wrap me-1 select-icon px-0" style="min-width:200px">
                        <select class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                            style="min-width:200px;padding-top:18px;padding-bottom:18px;">
                            <option value="">최근 학습 순</option>
                        </select>
                    </div>

                    <div class="col-auto px-0">
                        <label class="label-search-wrap">
                            <input id="event_inp_search" type="text"
                                class="lg-search border-gray rounded-pill text-m-20px"
                                placeholder="강의명, 과목, 선생님 이름을 검색해보세요." onkeyup="if(event.keyCode == 13){}">
                        </label>
                    </div>
                </div>
            </div>
            {{-- 52 --}}
            <div class="pt-5 mt-1">
                <div>
                    <table class="w-100 table-style table-h-96 ">
                        <colgroup>
                            <col style="width: 80px;">
                        </colgroup>
                        <thead class="modal-shadow-style rounded">
                            <tr class="text-sb-20px ">
                                <th class="text-end pe-4">강좌정보</th>
                                <th></th>
                                <th class="">수강현황</th>
                                <th>남은 수강일</th>
                                <th>수강기간</th>
                                <th>강좌 수강기간(일수)</th>
                                <th>최종 학습일</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-m-20px active">
                                <td class="">
                                    <div class="text-start h-center h-100 ps-lg-5 position-relative">
                                        <img src="{{ asset('images/subject_kor_icon.svg') }}" width="72">
                                        <divc
                                            class=" position-absolute top-0 start-0 bottom-0 primary-bg-text-hover rounded-4"
                                            style="width:6px"></divc>
                                    </div>
                                </td>
                                <td class="text-start">
                                    <b class="black-color text-sb-20px">만점왕 국어3-2 (2023)</b>
                                    <br>김팝콘 선생님
                                </td>
                                <td>
                                    <span class="text-sb-20px">1</span>
                                    <span class="text-sb-20px scale-text-gray_05">/ 13</span>
                                </td>
                                <td>
                                    <span class="fw-medium">26일</span>
                                </td>
                                <td>
                                    <span class="fw-medium scale-text-gray_05">23.07.30-23.11.24</span>
                                </td>
                                <td>
                                    <span class="fw-medium scale-text-gray_05">23.07.26-23.10.24</span>
                                </td>
                                <td>
                                    <span class="fw-medium scale-text-gray_05">23.08.01 12:30</span>
                                </td>
                            </tr>
                            {{-- 만료예정 --}}
                            <tr class="text-m-20px primary-bg-bg">
                                <td class="text-start ps-lg-5" colspan="2">
                                    김팝콘 학생의 진도율로<br>
                                    <b class="black-color text-sb-20px">23.11.24일 수강 완료 예정입니다.</b>
                                </td>
                                <td>
                                    <span class="text-sb-20px">1</span>
                                    <span class="text-sb-20px scale-text-gray_05">/ 13</span>
                                </td>
                                <td>
                                    <span class="fw-medium">20% 완료</span>
                                </td>
                                <td colspan="2" class="py-4 px-4">
                                    <div class="d-flex gap-1 pt-2 px-2 mx-1">
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill"
                                            style="height:28px;width:36px;line-height:1">월</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill"
                                            style="height:28px;width:36px;line-height:1">화</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill"
                                            style="height:28px;width:36px;line-height:1">수</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill"
                                            style="height:28px;width:36px;line-height:1">목</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill"
                                            style="height:28px;width:36px;line-height:1">금</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill"
                                            style="height:28px;width:36px;line-height:1">토</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill"
                                            style="height:28px;width:36px;line-height:1">일</span>
                                    </div>
                                    <div class="h-center pt-3 pb-2 px-2 mx-1">
                                        <svg width="100%" height="12" viewBox="0 0 100% 12" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect width="100%" height="12" rx="6" fill="white" />
                                            <rect width="20%" height="12" rx="6" fill="#FFC747" />
                                        </svg>
                                    </div>
                                </td>
                                <td>
                                    <button type="button"
                                        class="btn-xss-primary text-sb-20px rounded-3 scale-text-white"
                                        onclick="userlectShowVidoDetail(this);">
                                        강좌 상세보기
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 64px --}}
            <div>
                <div class="py-lg-4"></div>
                <div class="pt-lg-3"></div>
            </div>

            <div class="row mx-0">
                <div class="col-lg-3">
                </div>

                {{-- 페이징  --}}
                <div class="col d-flex justify-content-center">
                    <ul class="pagination col-auto" data-ul-userlect-page="1">
                        <button href="javascript:void(0)" class="btn p-0 prev" data-btn-userlect-page-prev="1"
                            onclick="noticeBoardSelect()">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                        </button>
                        <li class="page-item" hidden>
                            <a class="page-link" onclick="">0</a>
                        </li>
                        <span class="page" data-span-userlect-page-first="1" hidden
                            onclick="noticeBoardSelect(this.innerText)">0</span>
                        <span class="page_num page active">1</span>
                        <button href="javascript:void(0)" class="btn p-0 next" data-btn-userlect-page-next="1">
                            <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                        </button>
                    </ul>
                </div>

                <div class="col-lg-3">
                    {{-- 선택 플래너 수정 --}}
                    <button type="button" 
                    class="col-auto btn-line-ss-primary text-sb-20px rounded-3 btn btn-outline-light scale-text-gray_05 border">
                        선택 플래너 수정
                    </button>
                    {{-- 선택 강좌 삭제 --}}
                    <button type="button" 
                    class="col-auto btn btn-outline-light btn-line-ss-primary text-sb-20px rounded-3  scale-text-gray_05 border">
                        선택 강좌 삭제
                    </button>
                </div>
            </div>

        </section>
        {{-- 관심 강좌 --}}
        <section data-section-userlect-course-status="2" hidden>
            <div class="row mx-0">
                <aside class="col-lg-3 px-0 pe-3">
                    <div class="shadow-sm-2 p-4">
                        <div class="pt-2">
                            <button data-btn-userlect-aside-tab="like" onclick="userlectAsideTab(this);" class="btn btn-outline-primary-y ctext-gc1 w-100 border-0 rounded-4 p-4 h-center cfs-5 active">
                                <img src="{{ asset('images/window_hart_icon.svg') }}">
                                찜한 강좌
                            </button>
                        </div>
                        <div class="pt-2">
                            <button data-btn-userlect-aside-tab="often" onclick="userlectAsideTab(this)" class="btn btn-outline-primary-y ctext-gc1 w-100 border-0 rounded-4 h-center cfs-5 p-4">
                                <img src="{{ asset('images/eye_icon.svg') }}" alt="">
                                최근 많이 본 강좌
                            </button>
                        </div>
                    </div>
                </aside>
                <div class="col-lg px-0">
                    <section class="d-flex gap-1 justify-content-end pb-2">
                        <div class="col-auto d-inline-block select-wrap me-1 select-icon px-0" style="min-width:200px">
                            <select class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                                style="min-width:200px;padding-top:18px;padding-bottom:18px;">
                                <option value="">과목순</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="label-search-wrap">
                                <input id="userlect_input_search_str" 
                                type="text" class="lg-search border-gray rounded-pill text-m-20px" placeholder="강의명, 과목, 선생님 이름을 검색해보세요." 
                                onkeyup="if(event.keyCode == 13){}">
                            </label>
                        </div>
                    </section>
                    
                    <section class="pt-4" data-section-userlect-aside-sub="like">
                        <div class="row row-cols-lg-3 mx-0 mt-2 gx-4">
                            <div class="col ps-0">
                                <div>
                                    <div style="width:100%;height:240px" class="bg-gc5 rounded-3">
                                        <img src="{{ asset('images/temp_video_th.png') }}" width="100%">
                                    </div>
                                </div>
                                <div class="mt-3 pt-1">
                                    <div class="pb-2">
                                        <span class="text-b-20px scale-text-black">만점왕 국어 3-1</span>
                                    </div>
                                    <div>
                                        <span class="text-b-20px scale-text-gray_05">이선희 선생님</span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </section>
                    <section class="pt-4" data-section-userlect-aside-sub="often" hidden>
                        <div>
                            <table class="w-100 table-style table-h">
                                <thead class="modal-shadow-style rounded">
                                  <tr class="text-sb-20px ">
                                    <th>강의정보</th>
                                    <th>수강현황</th>
                                    <th>최종학습일</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr class="text-m-20px">
                                    <td class="px-4">
                                        <div class="row mx-0 ps-2 py-3 my-1">
                                            <div class="col-auto h-center px-0 rounded-4 position-relative">
                                                <img src="{{ asset('images/temp_video_th.png') }}" width="120px" class="rounded-4">
                                            </div>
                                            <div class="col px-0 ps-3 w-center flex-column">
                                                <div class="text-start">
                                                    <span class="test-b-20px">만점왕 국어 3-2(2023)</span>
                                                </div>
                                                <div class="text-start">
                                                    <span class="text-m-18px scale-text-gray_05">김팝콘 선생님</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium">1</span>
                                        <span class="fw-medium scale-text-gray_05">/</span>
                                        <span class="fw-medium scale-text-gray_05">13</span>
                                    </td>
                                    <td>
                                        <span class="text-b-20px scale-text-gray_05">23.08.01 12:30</span>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>

                        {{-- 페이징 --}}
                        <div class="d-flex justify-content-center pt-5 mt-1">
                            <ul class="pagination col-auto" data-ul-userlect-page="2">
                                <button href="javascript:void(0)" class="btn p-0 prev" data-btn-userlect-page-prev="2"
                                    onclick="noticeBoardSelect()">
                                    <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                                </button>
                                <li class="page-item" hidden>
                                    <a class="page-link" onclick="">0</a>
                                </li>
                                <span class="page" data-span-userlect-page-first="2" hidden
                                    onclick="noticeBoardSelect(this.innerText)">0</span>
                                <span class="page_num page active">1</span>
                                <button href="javascript:void(0)" class="btn p-0 next" data-btn-userlect-page-next="2">
                                    <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                                </button>
                            </ul>
                        </div>

                    </section>
                </div>
            </div>
        </section>
        {{-- 미수강 --}}
        <section data-section-userlect-course-status="3" hidden>
            <div class="row mx-0 pb-4 mb-1">
                <div class="col row align-items-center ">
                    <span class="col-auto  text-sb-24px text-danger px-0 ps-2 ms-1">
                        총 
                        <span data-span-userlect-ready-cnt-sub="3">0</span>
                        개
                    </span>
                    <span class="col-auto  text-sb-24px scale-text-gray_05 px-1">의 강의가 있습니다.</span>
                </div>
                <div class="col-auto row mx-0 p-0 gap-2">
                    <div class="col-auto d-inline-block select-wrap me-1 select-icon px-0" style="min-width:200px">
                        <select data-select-userlect-subject-code="3" onchange="userlectLectureReadySelect()"
                        class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                            style="min-width:200px;padding-top:18px;padding-bottom:18px;">
                            @if(!empty($subject_codes))
                                @foreach ($subject_codes as $subject_code)
                                    <option value="{{$subject_code->id}}">{{$subject_code->code_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-auto row mx-0 p-0 gap-2">
                    <div class="col-auto d-inline-block select-wrap me-1 select-icon px-0" style="min-width:200px">
                        <select data-select-now-week onchange="userlectLectureReadySelect()"
                        class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                            style="min-width:200px;padding-top:18px;padding-bottom:18px;">
                        </select>
                    </div>
                </div>
            </div>
            <div class="row row-cols-lg-4 mx-0 gx-4" data-div-userlect-sub-bundle="3">
                <div class="col" data-div-userlect-sub-row="3" hidden>
                    <input type="hidden" data-lecture-seq>
                    <input type="hidden" data-lecture-detail-seq>
                    <div class="position-relative">
                        <img src="https://sdang.acaunion.com/images/temp_pay_img.png" class="card-img-top" alt="...">
                        <div class="progress rounded-0" role="progressbar" style="height:12px;margin-top: -2px;">
                            <div class="progress-bar bg-study-0 rounded-0" style="width: 25%"></div>
                        </div>
                        <div class="card-body p-4 border-start border-end border-bottom rounded-bottom-4">
                            <div class="row">
                                <div class="col-auto cfs-5 fw-bold">
                                    <span data-idx>8</span>
                                    <span data-lecture-name></span>
                                    <span data-lecture-detail-name></span>
                                </div>
                            </div>
                            <div class="ctext-gc0 cfs-6 mt-2 mb-2" data-lecture-detail-content>
                                글을 읽고 인물의 의견과 그 까닭 알기 글을 읽고 인물의 의견과 그까닭...
                            </div>
                            <div class="mt-4 d-flex gap-1">
                                <span class="studey-before cfs-6 me-1">학습 전</span>
                                <button class="btn rounded-pill border cfs-6 fw-medium ctext-bc0 h-center pe-3" style="width:134px">
                                    <img src="https://sdang.acaunion.com/images/video_play_icon.svg" width="24">
                                    <span>학습하기</span>
                                </button>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            {{-- 52 --}}
            <div>
                <div class="py-lg-4"></div>
                <div class="pt-lg-3"></div>
            </div>
            {{-- 페이징 --}}
            <div class="d-flex justify-content-center">
                <ul class="pagination col-auto" data-ul-userlect-page="3">
                    <button href="javascript:void(0)" class="btn p-0 prev" data-btn-userlect-page-prev="3"
                        onclick="noticeBoardSelect()">
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                    </button>
                    <li class="page-item" hidden>
                        <a class="page-link" onclick="">0</a>
                    </li>
                    <span class="page" data-span-userlect-page-first="3" hidden
                        onclick="noticeBoardSelect(this.innerText)">0</span>
                    <span class="page_num page active">1</span>
                    <button href="javascript:void(0)" class="btn p-0 next" data-btn-userlect-page-next="3">
                        <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                    </button>
                </ul>
            </div>

        </section>
        {{-- 재수강 --}}
        <section data-section-userlect-course-status="4" hidden>
            <div class="row mx-0 pb-4 mb-1">
                <div class="col row align-items-center ">
                    <span class="col-auto  text-sb-24px text-danger px-0 ps-2 ms-1">총 00개</span>
                    <span class="col-auto  text-sb-24px scale-text-gray_05 px-1">의 강좌가 있습니다.</span>
                </div>
                <div class="col-auto row mx-0 p-0 gap-2">
                    <div class="col-auto d-inline-block select-wrap me-1 select-icon px-0" style="min-width:200px">
                        <select class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                            style="min-width:200px;padding-top:18px;padding-bottom:18px;">
                            @if(!empty($subject_codes))
                                @foreach ($subject_codes as $subject_code)
                                    <option value="{{$subject_code->id}}">{{$subject_code->code_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-auto row mx-0 p-0 gap-2">
                    <div class="col-auto d-inline-block select-wrap me-1 select-icon px-0" style="min-width:200px">
                        <select data-select-now-week
                        class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                            style="min-width:200px;padding-top:18px;padding-bottom:18px;">
                        </select>
                    </div>
                </div>
            </div>
            <div class="row row-cols-lg-4 mx-0 gx-4">
                <div class="col">
                    <div class="position-relative">
                        <img src="https://sdang.acaunion.com/images/temp_pay_img.png" class="card-img-top" alt="...">
                        <div class="progress rounded-0" role="progressbar" style="height:12px;margin-top: -2px;">
                            <div class="progress-bar bg-study-2 rounded-0" style="width: 25%"></div>
                        </div>
                          
                        <div class="card-body p-4 border-start border-end border-bottom rounded-bottom-4">
                            <div class="row">
                                <div class="col-auto cfs-5 fw-bold">
                                    [8단원] 의견이 있어요.
                                </div>
                            </div>
                            <div class="ctext-gc0 cfs-6 mt-2 mb-2">
                                글을 읽고 인물의 의견과 그 까닭 알기 글을 읽고 인물의 의견과 그까닭...
                            </div>
                            <div class="mt-4 d-flex gap-1">
                                <span class="studey-completion">학습 완료</span>
                                <button class="btn rounded-pill border cfs-6 fw-medium ctext-bc0 h-center pe-3" style="width:134px">
                                    <img src="https://sdang.acaunion.com/images/video_play_icon.svg" width="24">
                                    <span>학습하기</span>
                                </button>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            {{-- 52 --}}
            <div>
                <div class="py-lg-4"></div>
                <div class="pt-lg-3"></div>
            </div>

            {{-- 페이징 --}}
            <div class="d-flex justify-content-center">
                <ul class="pagination col-auto" data-ul-userlect-page="4">
                    <button href="javascript:void(0)" class="btn p-0 prev" data-btn-userlect-page-prev="4"
                        onclick="noticeBoardSelect()">
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                    </button>
                    <li class="page-item" hidden>
                        <a class="page-link" onclick="">0</a>
                    </li>
                    <span class="page" data-span-userlect-page-first="4" hidden
                        onclick="noticeBoardSelect(this.innerText)">0</span>
                    <span class="page_num page active">1</span>
                    <button href="javascript:void(0)" class="btn p-0 next" data-btn-userlect-page-next="4">
                        <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                    </button>
                </ul>
            </div>
        </section>

    </div>



    {{-- 모달 / 강좌 상세 --}}
    <div class="modal fade" id="userlect_modal_lecture_detail" data-bs-backdrop="static" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog  modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">강좌 상세</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick=""></button>
                </div>
                <div class="modal-body">
                    {{-- 상단 / 동영상 , 테이블 --}}
                    <div class="row">
                        <div class="row col-4">
                            <div class="fs-6">만점왕 수학 3-2(2023)</div>
                            <div style="font-size:13px">만점왕</div>
                            <div>
                                <iframe width="300" height="167" src="https://www.youtube.com/embed/P5l2heNKK_U?list=PLraYM6paW-C1MdyzXETQNQWwWtFAefSLz" title="[고등예비과정 수학 I] 01강 다항식의 연산 (1)" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="col pt-5">
                            <table class="table table-border align-middle border">  
                                <tr>
                                    <th class="table-light p-3">선생님</th>
                                    <td class="ps-3">나소은 선생님</td>
                                    <th class="table-light p-3">학습자 수</th>
                                    <td class="ps-3">26,900명</td>
                                </tr>
                                <tr>
                                    <th class="table-light p-3">수강대상</th>
                                    <td class="ps-3">초3</td>
                                    <th class="table-light p-3">강좌수준</th>
                                    <td class="ps-3">중급</td>
                                </tr>
                                <tr>
                                    <th class="table-light p-3">시리즈</th>
                                    <td class="ps-3">만점왕</td>
                                    <th class="table-light p-3">수강기간</th>
                                    <td class="ps-3">105일</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    {{-- 강의 리스트 ot부터 --}}
                    <div class="row m-0 p-0">
                        <div class="border p-0">
                            <div class="p-3 bg-white" style="z-index: 2;position: relative;">
                                총 30 / 30 개의 강의가 완강 되었습니다.
                            </div>
                            <div>
                                <table class="table table-border border mb-0">
                                    <thead class="table-light" >
                                        <tr style="z-index: 2;position: relative;">
                                            <td>강의명</td>
                                            <td>강의시간</td>
                                            <td>교재페이지</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="z-index:2;position: relative;">
                                            <td data="#강의명"></td>
                                            <td data="#강의시간"></td>
                                            <td data="#교재페이지"></td>
                                            <td data="#강의영상보기">
                                                <button class="btn btn-outline-primary rounded-circle" style="width: 40px;height:40px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 17 17">
                                                        <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
                                                    </svg>
                                                </button>
                                            </td>
                                            <td data="#강의오픈">
                                                {{-- UP --}}
                                                <button class="btn btn-up" onclick="userlectLectureDetailTrClick(this)" hidden>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-up" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z"/>
                                                    </svg>
                                                </button>
                                                {{-- DOWN --}}
                                                <button class="btn btn-down" onclick="userlectLectureDetailTrClick(this)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot hidden>
                                        <tr class="copy_tr_down table-light" style="z-index:1">
                                            <td colspan="5">
                                                <div class="row p-2">
                                                    <div class="col-auto">
                                                        <iframe width="200" height="111" src="https://www.youtube.com/embed/P5l2heNKK_U?list=PLraYM6paW-C1MdyzXETQNQWwWtFAefSLz" title="[고등예비과정 수학 I] 01강 다항식의 연산 (1)" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                                    </div>
                                                    <div class="col">
                                                        <div class="row">
                                                            <div class="col-2">강의설명</div>
                                                            <div class="col text-secondary">[세자리수]x[한자리수]</div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-2">키워드</div>
                                                            <div class="col text-secondary">곱셈, 곱, (세 자리수)x(한 자리수)</div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-2">교과분류</div>
                                                            <div class="col text-secondary">3학년 > 수학 > 수와 연산 > 수의 연산 > 자연수의 곱셈과 나눗셈 > 자연수의 곱셈(2) > 자연수끼리의 곱셈</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                        onclick="">닫기</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 160px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>

    </div>
    
    
    

    <script>
        // ready
        document.addEventListener("DOMContentLoaded", function(){
            // #userlect_sel_region 의 option이 1개이면서 value가 != "" 이면
            const userlect_sel_region = document.querySelector("#userlect_sel_region");
            const usr_options = userlect_sel_region.querySelectorAll("option");
            if(usr_options.length == 1 && userlect_sel_region.value != ""){
                userlectTeamSelect(userlect_sel_region);
            }
            userlectSelectTagMakeNowWeek();
        });

        // 팀 불러오기
        function userlectTeamSelect(vthis){
            const region_seq = vthis.value;
            const team_tag = document.querySelector("#userlect_sel_team");
            // 팀비우기.
            team_tag.innerHTML = "<option value=''>팀</option>";
            // 공백 체크
            if(region_seq == ""){ return; }
            // 팀 불러오기
            const page = "/manage/useradd/team/select";
            const parameter = {
                region_seq : region_seq
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    // result.teams option 추가
                    const teams = result.resultData;
                    teams.forEach(function(team){
                        const option = document.createElement("option");
                        option.value = team.team_code;
                        option.innerText = team.team_name;
                        team_tag.appendChild(option);
                    });
                }
            });
        }

        // 상단 회원 검색
        function userlectUserSelect(){
            const region_seq = document.querySelector("#userlect_sel_region").value;
            const team_code = document.querySelector("#userlect_sel_team").value;
            const search_type = document.querySelector("#userlect_sel_search_type").value;
            const search_str = document.querySelector("#userlect_input_search_str").value;

            //region_seq 가 없으면 return
            if(region_seq == ""){ toast("소속을 선택해주세요."); return; }

            const page = "/manage/user/lecture/user/select";
            const parameter = {
                region_seq : region_seq,
                team_code : team_code,
                search_type : search_type,
                search_str : search_str
            };
            const tby_user = document.querySelector("#userlect_tby_user");
            tby_user.querySelector(".copy_tr_user").hidden = false;
            document.querySelector("#userlect_div_user_empty").hidden = true;

            queryFetch(page, parameter, function(result){
                //초기화
                const tr_user = tby_user.querySelector(".copy_tr_user").cloneNode(true);
                tby_user.innerHTML = "";
                tby_user.appendChild(tr_user);
                tr_user.hidden = true;
                
                if((result.resultCode||'') == 'success'){
                    result.students.forEach(function(user){
                        const tr = tr_user.cloneNode(true);
                        tr.classList.remove("copy_tr_user");
                        tr.classList.add("tr_user");
                        tr.hidden = false;
                        //로딩바 제거
                        tr.querySelectorAll(".loding_place").forEach(function(place){
                            place.remove();
                        });
                        tr.querySelector(".school_name").innerText = user.school_name;
                        tr.querySelector(".grade").innerText = user.grade;
                        tr.querySelector(".student_name").innerText = user.student_name;
                        tr.querySelector(".student_id").innerText = user.student_id;
                        tr.querySelector(".student_phone").innerText = user.student_phone;
                        tr.querySelector(".payment_last_date").innerText = user.payment_last_date || '';
                        tr.querySelector(".parent_name").innerText = user.parent_name;
                        tr.querySelector(".parent_phone").innerText = user.parent_phone;

                        tr.querySelector(".student_seq").value= user.id;
                        tr.querySelector(".region_name").value = user.region_name;
                        tr.querySelector(".team_name").value = user.team_name;
                        tby_user.appendChild(tr);
                    });
                }
                //if tr_user 없으면 회원 목록이 없습니다.
                if(tby_user.querySelectorAll(".tr_user").length == 0){
                    document.querySelector("#userlect_div_user_empty").hidden = false;
                }else{
                    document.querySelector("#userlect_div_user_empty").hidden = true;
                }
            });
        }

        // 회원 tr 클릭
        function userlectUserTrClick(vthis) {  
            // vthis 에 table-primary 토글
            const is_active = vthis.classList.contains("active");
            const trs = document.querySelectorAll(".tr_user");
            trs.forEach(function(tr){
                tr.classList.remove("active");
            });
            if(!is_active){
                vthis.classList.add("active");
                const region_name = vthis.querySelector(".region_name").value;
                const team_name = vthis.querySelector(".team_name").value;
                const student_name = vthis.querySelector(".student_name").innerText;
                const school_name = vthis.querySelector(".school_name").innerText;
                const student_seq = vthis.querySelector(".student_seq").value;
                document.querySelector("#userlect_span_region_name").innerText = region_name;
                document.querySelector("#userlect_span_team_name").innerText = team_name;
                document.querySelector("#userlect_span_student_name").innerText = student_name;
                document.querySelector("#userlect_span_school_name").innerText = school_name;

                // 선택 회원의 수강완료, 관심강좌, 미수강강좌, 재수강 강좌 수치 가져오기.
                document.querySelectorAll("[data-student-name]").forEach(function(item){
                    item.innerText = student_name;
                });
                userlectLectureCntSelect(student_seq);
            }            
        }

        // 메인(강좌[수강,완료,관심,미수강]]별) TAB
        function userlectCourseStatusMainTab(vthis){
            // 우선 data-div-userlect-course-status-main-tab 모두 비활성화, 후 vthis 활성화
            document.querySelectorAll('[data-div-userlect-course-status-main-tab]').forEach(function(item) { 
                item.classList.remove('active');
            });
            vthis.classList.add('active');
            // 탭에따른 펑션 실행
            const type = vthis.getAttribute('data-div-userlect-course-status-main-tab');
            userlectCourseStatusMainTabSub(type);
        }

        // 메인(강좌[수강,완료,관심,미수강]]별) TAB SUB
        function userlectCourseStatusMainTabSub(type) {
            // data-section-userlect-course-status 모두 숨김처리.
            document.querySelectorAll('[data-section-userlect-course-status]').forEach(function(item) {
                item.hidden = true;
            });
            document.querySelector('[data-section-userlect-course-status="' + type + '"]').hidden = false;

            // 수강완료 강좌
            if(type == '1'){

            }
            // 관심강좌 
            else if(type == '2'){

            }
            // 미수강
            else if(type == '3'){
                userlectLectureReadySelect();
            }
            // 재수강
            else if(type == '4'){

            }
        }

        // 관심강좌 > 찜한 강좌, 최근 많이 본 강좌 TAB
        function userlectAsideTab(vthis){
            const type = vthis.getAttribute('data-btn-userlect-aside-tab');
            // data-section-userlect-aside-sub 모두 숨김처리.
            document.querySelectorAll('[data-section-userlect-aside-sub]').forEach(function(item) {
                item.hidden = true;
            });
            document.querySelector('[data-section-userlect-aside-sub="' + type + '"]').hidden = false;
        }

        // 강좌 카운트 가져오기.
        function userlectLectureCntSelect(student_seq){
            const page = "/manage/user/lecture/cnt/select";
            const parameter = {
                student_seq:student_seq
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    const detail = result.student_lecture_details;
                        const complete_cnt = detail.complete_cnt || '0';
                        const like_cnt = detail.like_cnt || '0';
                        const ready_cnt = detail.ready_cnt|| '0';
                        const again_cnt = detail.again_cnt|| '0';

                        document.querySelector("[data-span-userlect-complete-cnt]").innerText = complete_cnt;
                        document.querySelector("[data-span-userlect-like-cnt]").innerText = like_cnt;
                        document.querySelector("[data-span-userlect-ready-cnt]").innerText = ready_cnt;
                        document.querySelector("[data-span-userlect-again-cnt]").innerText = again_cnt;
                }else{

                }
            });
        }

        // 미수강 강좌 가져오기
        function userlectLectureReadySelect(){
            const main_div = document.querySelector("[data-section-userlect-course-status='3']");
            // data-select-now-week
            const sel_date_between = main_div.querySelector("[data-select-now-week]").value;
            const start_date = sel_date_between.split("|")[0];
            const end_date = sel_date_between.split("|")[1];
            const subject_seq = main_div.querySelector("[data-select-userlect-subject-code='3']").value;

            // 전송
            const page = "/manage/learning/student/do/lecture/select";
            const parameter = {
                end_search_date : end_date,
                start_search_date : start_date,
                step3_type : "nodo",
                student_seqs : document.querySelector(".tr_user.active .student_seq").value,
                subject_seq : subject_seq
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    const cnt = result.student_lecture_details.length;
                    document.querySelector("[data-span-userlect-ready-cnt-sub='3']").innerText = cnt;

                    // data-div-userlect-sub-bundle="3"
                    // data-div-userlect-sub-row="3"
                    // 번들 초기화
                    const bundle = main_div.querySelector("[data-div-userlect-sub-bundle='3']");
                    const row = bundle.querySelector("[data-div-userlect-sub-row='3']").cloneNode(true);
                    bundle.innerHTML = "";
                    bundle.appendChild(row);
                    
                    result.student_lecture_details.forEach(function(detail){
                        const row_clone = row.cloneNode(true);
                        row_clone.setAttribute("data-div-userlect-sub-row","clone");
                        row_clone.hidden = false;

                        const before_txt = detail.idx >= 2 ? `[${(detail.idx-1)} 강]`:'';
                        row_clone.querySelector("[data-idx]").innerText = before_txt;
                        row_clone.querySelector("[data-lecture-seq]").value = detail.lecture_seq;
                        row_clone.querySelector("[data-lecture-detail-seq]").value = detail.lecture_detail_seq;
                        row_clone.querySelector("[data-lecture-name]").innerText = detail.lecture_name;
                        row_clone.querySelector("[data-lecture-detail-name]").innerText = detail.lecture_detail_name;
                        // row_clone.querySelector("[data-lecture-detail-content]").innerText = detail.lecture_detail_content;
                        bundle.appendChild(row_clone);
                    });
                }else{

                }
            });
        }

        // 현재 년월의 주차를 select에 추가.
        function userlectSelectTagMakeNowWeek(){
            // all [data-select-now-week] 에 만들어서 넣기.
            // 오늘을 포함한 이번주의 월요일부터 일요일까지의 날짜를 구한다.
            const today = new Date();
            const option1 = userlectSelectWeekDate(today);
            const option2 = userlectSelectWeekDate(new Date(today.setDate(today.getDate() - 7)));
            const option3 = userlectSelectWeekDate(new Date(today.setDate(today.getDate() - 7)));
            const option4 = userlectSelectWeekDate(new Date(today.setDate(today.getDate() - 7)));
            const option5 = userlectSelectWeekDate(new Date(today.setDate(today.getDate() - 7)));

            const select = document.querySelectorAll("[data-select-now-week]");
            select.forEach(function(item){
                item.innerHTML = `
                    <option value="${option1.date}">${option1.month} ${option1.week}주차</option>
                    <option value="${option2.date}">${option2.month} ${option2.week}주차</option>
                    <option value="${option3.date}">${option3.month} ${option3.week}주차</option>
                    <option value="${option4.date}">${option4.month} ${option4.week}주차</option>
                    <option value="${option5.date}">${option5.month} ${option5.week}주차</option>
                `;
            });
        }
        function userlectSelectWeekDate(sel_date){
            const today = sel_date;
            const start_date = new Date(today.setDate(today.getDate() - today.getDay()));
            const end_date = new Date(today.setDate(today.getDate() + 6));

            // 오늘 월의 몇주차인지 구한다.
            const now_week = Math.ceil((today.getDate() + 6 - today.getDay()) / 7);

            const rtn_date = {
                month: end_date.format('yyyy년 MsM월'),
                date :`${start_date.format('yyyy-MM-dd')}|${end_date.format('yyyy-MM-dd')}`,
                week : now_week
            };
            return rtn_date;
        }
    </script>
@endsection