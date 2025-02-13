@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
사용자 상담 관리
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <div class="col-12 pe-3 ps-3 position-relative">
        {{-- 상단 회원 검색 --}}
        <div class="row">
            <div class="row p-0 border">
                <div class="bg-light col-auto p-3">회원 검색</div>
                <div class="row col gap-2 align-items-center justify-content-between">
                    <span class="w-auto p-3">소속</span>
                    {{-- region --}}
                    <select class="form-select form-select-sm col hpx-40" id="usercsl_sel_region"
                        onchange="usercslTeamSelect(this)">
                        <option value="">소속</option>
                        @if (!empty($regions))
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    {{-- team --}}
                    <select class="form-select form-select-sm col hpx-40" id="usercsl_sel_team">
                        <option value="">팀</option>
                    </select>
                    {{-- name, id, 전화번호 --}}
                    <select class="form-select form-select-sm col hpx-40" id="usercsl_sel_search_type">
                        <option value="student_name">이름</option>
                        <option value="student_phone">전화번호</option>
                        <option value="parent_name">학부모</option>
                        <option value="parent_phone">학부모 전화번호</option>
                    </select>
                    {{-- input serach str --}}
                    <input type="text" class="form-control form-control-sm col hpx-40" placeholder="검색어를 입력하세요."
                        id="usercsl_input_search_str">
                    {{-- search btn --}}
                    <button type="button" class="btn btn-primary col-1 me-3" onclick="usercslUserSelect();">검색</button>
                </div>
            </div>

            {{-- 회원 목록 --}}
            <div class="row p-0 tableFixedHead overflow-auto border" style="max-height:160px;">
                <table class="table table-bordered m-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>학교/학년</th>
                            <th>회원명/아이디</th>
                            <th>휴대전화</th>
                            <th>최근 결제일자</th>
                            <th>학부모</th>
                            <th>학부모 연락처</th>
                        </tr>
                    </thead>
                    <tbody id="usercsl_tby_user">
                        <tr class="copy_tr_user" hidden onclick="usercslUserTrClick(this)">
                            <td data="#학교/학년">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                                <span class="school_name"></span>
                                <span class="grade"></span>
                            </td>
                            <td data="#회원명/아이디">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                                <span class="student_name"></span>
                                <span class="student_id"></span>
                            </td>
                            <td data="#휴대전화">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                                <span class="student_phone"></span>
                            </td>
                            <td data="#최근 결제일자">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                                <span class="payment_last_date"></span>
                            </td>
                            <td data="#학부모">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                                <span class="parent_name"></span>
                            </td>
                            <td data="#학부모 연락처">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                                <span class="parent_phone"></span>
                            </td>
                            <input type="hidden" class="region_name">
                            <input type="hidden" class="team_name">
                        </tr>
                    </tbody>
                </table>
                {{-- 회원 목록이 없습니다. --}}
                <div class="col-12 text-center p-3" id="usercsl_div_user_empty">
                    <span>회원 목록이 없습니다.</span>
                </div>
            </div>

            {{-- 선택시 소속 정보 가져오기. --}}
            <div class="row p-0 mt-2 ">
                <div class="row col border ms-0" style="min-height: 54px">
                    <div class="col p-3">
                        <span id="usercsl_span_region_name"></span>
                        <span id="usercsl_span_team_name"></span>
                    </div>
                    <div class="col p-3">
                        <span id="usercsl_span_student_name"></span>
                        <span id="usercsl_span_school_name"></span>
                    </div>
                    <div class="col p-3">
                        <span id="usercsl_span_goods_name"></span>
                        <span id="usercsl_span_goods_expire_date"></span>
                    </div>
                    <div class="col p-3">
                        <span id="usercsl_span_parent_name"></span>
                    </div>
                    <div class="col p-3">
                        <span id="usercsl_span_parent_phone"></span>
                    </div>
                </div> 
            </div>
            <div class="row p-0 mt-2 ">
                <div class="row col-6 border ms-0" style="min-height: 54px">
                    <div class="col p-3">
                        <span id="usercsl_span_region_name2"></span>
                        <span id="usercsl_span_team_name2"></span>
                    </div>
                    <div class="col p-3">
                        <span id="usercsl_span_teacher_name"></span>
                    </div>
                    <div class="col p-3">
                        <span id="usercsl_span_teacher_phone"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 상담 목록 검색 --}}
        <div class="row p-0 mt-2">
            <div class="row col p-0">
                <select class="form-select form-select-sm w-auto hpx-40">
                    <option value="">결제일자 최근순</option>
                </select>
            </div>
            <div class="row col gap-1 align-items-center justify-content-end">
                <input type="date" class="form-control form-control-sm w-auto hpx-40"
                    value="{{ date('Y-m-d', strtotime('-1 week')) }}">
                <span class="col-auto">~</span>
                <input type="date" class="form-control form-control-sm w-auto hpx-40" value="{{ date('Y-m-d') }}">
                <button type="button" class="btn btn-primary col-auto me-3">검색</button>
            </div>
        </div>
         
        <div class="row p-0 mt-2">
            <div class="row p-0 overflow-auto border tableFixedHead" style="max-height:360px;">
                <table class="table table-bordered text-center m-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">
                                <input type="checkbox" class="form-check-input" id="usercsl_check_all">
                            </th>
                            <th>분류</th>
                            <th>상담번호</th>
                            <th>최근상담일자</th>
                            <th>다음상담예정일</th>
                            <th>상담일지</th>
                        </tr>
                    </thead>
                    <tbody id="usercsl_tby_counsel">
                        <tr class="copy_tr_counsel" hidden>
                            <td data="#chk">
                                <input type="checkbox" class="form-check-input chk">
                            </td>
                            <td data="#분류">
                                <span class="placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </span>
                            </td>
                            <td data="#상담번호">
                                <span class="placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </span>
                            </td>
                            <td data="#최근상담일자">
                                <span class="placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </span>
                            </td>
                            <td data="#다음상담예정일">
                                <span class="placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </span>
                            </td>
                            <td data="#상담일지">
                                <span class="placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="col-12 text-center p-3" id="usercsl_div_counsel_empty">
                    <span>상담 목록이 없습니다.</span>
                </div>
            </div>
            
            {{-- 총건수, 엑샐다운 버튼 --}}
            <div class="row p-0 mt-3">
                <div class="row col align-items-center fs-5">
                    <span class="col-auto">총 건수 : <span id="usercsl_span_total_count">0</span>건</span>
                </div>
                <div class="row col align-items-center justify-content-end">
                    <button type="button" class="btn btn-outline-success col-auto me-3">검색결과 엑셀파일 다운받기</button>
                </div>
            </div>
        </div>
    </div>
@endsection