@extends('layout.layout')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<div class="col-12 pe-3 ps-3 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        </div>

        {{-- 상단 이용권 ... 카테고리? --}}
        <div class="row mt-2 mb-2 pe-3">
            <div class="row ms-1">
                <button class="btn btn-outline-primary active rounded-0" style="width:100px;">이용권</button>
            </div>
        </div>
        {{-- 상단 체크 표시 --}}
        <div class="row mt-2 mb-2 pe-3">
            <div class="row col p-2 ps-3 ms-3 border border-secondary mb-2 me-0">
                <span class="col-auto">
                    <input type="checkbox" id="pay_chk_top_category">
                    <label for="pay_chk_top_category">전체</label>
                </span>
            </div>
        </div>
        {{-- 상단 조회 기능 --}}
        <div class="row gap-3 px-3">
            <select class="col border border-secondary form-select col-auto" style="width:auto;">
                    <option value="user_id">회원ID</option>
                </select>
            <input id="authority_inp_search_str" type="text" placeholder="검색어를 입력해주세요." class="input col"
                onkeyup="if(event.keyCode == 13) paySelect();">
            <button class="btn btn-outline-primary rounded col-1" onclick="paySelect();">조회</button>
        </div>

        {{-- 검색 기능 --}}
        <div class="row mt-2 mb-2 pe-3">
            <div class="col">
                <select class="col border border-secondary form-select" style="width:auto">
                    <option value="">결제일자 최근순</option>
                </select>
            </div>
            <div class="col d-flex justify-content-end gap-3">
                <input type="date" class="form-control" style="width:auto;" value="{{ date("Y-m-d", strtotime("-14 day")) }}">
                <input type="date" class="form-control" style="width:auto;" value="{{ date("Y-m-d") }}">
                <button class="btn btn-primary float-end" style="width:auto">검색</button>
            </div>
        </div>

        {{-- 권한?/그룹 리스트 table --}}
        <div class="row mb-2">
            <div class="col-12 tableFixedHead border-top border-bottom overflow-auto" style="height: calc(100vh - 420px)">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            {{-- input checkbox, 소속, 회원명, 이용권, 금액, 할부, 카드사, 승인번호, 상태, 승인(싪패)일자, 결제취소 --}}
                            <th style="width: 5%">
                                <input type="checkbox" id="pay_chk_all">
                            </th>
                            <th style="width: 50px">번호</th>
                            <th >소속</th>
                            <th >회원명</th>
                            <th >이용권</th>
                            <th >금액</th>
                            <th >할부</th>
                            <th >카드사</th>
                            <th >승인번호</th>
                            <th >상태</th>
                            <th >승인(실패)일자</th>
                            <th >결제취소</th>
                        </tr>
                    </thead>
                    <tbody id="pay_tby_list">
                        <tr class="copy_tr_pay" hidden>
                            <td class="pay_chk_category">
                                <input type="checkbox" class="pay_chk">
                            </td>
                            <td class="idx" data="#번호"></td>
                            <td data="#소속">
                                <span class="region_name"></span>
                                <span class="team_name"></span>
                            </td>
                            <td data="#회원명">
                                <span class="user_name"></span>
                                (<span class="user_id"></span>)
                            </td>
                            <td class="goods_name" data="#이용권"></td>
                            <td class="goods_price" data="#금액"></td>
                            <td class="card_inst" data="#할부"></td>
                            <td class="card_name" data="#카드사"></td>
                            <td class="card_no" data="#승인번호"></td>
                            <td data="#상태">
                                <span class="status_str" ></span>
                                <span class="status_num" ></span>
                            </td>
                            <td data="#승인(실패)일자">
                                <span class="sale_date"></span>
                            </td>
                            <td data="#결제취소">
                                <button class="btn btn-outline-secondary btn-sm">결제취소</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center" id="pay_div_list_none" hidden>
                    <span>조회된 내용이 없습니다.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row col-12">
        <div class="row col gap-2">
            <div class="col-3 d-flex flex-column gap-1">
                <button class="btn btn-outline-secondary">거래명세서 발급</button>
                <button class="btn btn-outline-secondary">카드매출전표 발급</button>
            </div>
            <div class="row col align-items-center">
                <span class="fs-5">
                    총 <span>0</span> 건 <span>0</span> 원
                </span>
            </div>
        </div>
        <div class="row col align-items-center justify-content-end">
            <button class="btn btn-outline-success col-auto">검색결과 엑셀파일 다운로드</button>
        </div>
    </div>

    <script>
        
    </script>

@endsection