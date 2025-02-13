@extends('layout.layout')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <div class="row ps-5 pe-3 pt-5">
        <div class="row">
            <h5 class="row fw-bold">결제 상세 정보</h5>
        </div>
        <div class="row">
            <div class="row p-0 border">
                {{-- 대상회원 / 아이디 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        대상회원 / 아이디</div>
                    <div class="p-2 text-center col border d-flex align-items-center">
                        <span class="fw-bold student_name">#이름</span>
                        <span class="fw-bold student_id">#ID</span>
                        <div class="col text-end">
                            <button class="btn btn-sm btn-outline-secondary px-4 rounded-4">수정</button>
                        </div>
                    </div>
                </div>
                {{-- 결제회원/아이디 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        결제회원/아이디</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="fw-bold parent_name">#이름</span>
                        <span class="fw-bold parent_id">#ID</span>
                        <div class="col text-end">
                            <button class="btn btn-sm btn-outline-secondary px-4 rounded-4">수정</button>
                        </div>
                    </div>
                </div>
                {{-- 주문번호 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        주문번호</div>
                    <div class="p-2 text-center col border d-flex gap-2 align-items-center ">
                        <button class="btn btn-sm btn-success rounded-4 px-4">재등록</button>
                        <span class="accept_no">#202311111111</span>
                    </div>
                </div>
                {{-- 결제상태 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        결제상태</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="pay_status_str"></span>
                    </div>
                </div>
                {{-- 결제일시 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        결제일시</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="pay_sale_date"></span>
                    </div>
                </div>
                {{-- 결제금액 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        결제금액</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="">0</span>원
                        <div class="col text-end">
                            <button class="btn btn-sm btn-outline-secondary px-4 rounded-4">환불처리</button>
                        </div>
                    </div>
                </div>
                {{-- 이용권 | 한줄 모두 차지 --}}
                <div class="col-12 row p-0 m-0">
                    <div class="col-2 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        이용권</div>
                    <div class="p-2 text-center col border d-flex align-items-center bg-success-subtle">
                        <span class="goods_name">초등플러스</span>
                        <span class="goods_period">6개월</span>
                        <div class="col text-end">
                            <button class="btn btn-sm btn-light px-4 rounded-4">수정</button>
                        </div>
                    </div>
                </div>
                {{-- 이용기간(시작) --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        이용기간(시작)</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="goods_start_date"></span>
                        <div class="col text-end">
                            <button class="btn btn-sm btn-outline-secondary px-4 rounded-4">수정</button>
                        </div>
                    </div>
                </div>
                {{-- 이용기간(종료) --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        이용기간(종료)</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="goods_end_date"></span>
                        <div class="col text-end">
                            <button class="btn btn-sm btn-outline-secondary px-4 rounded-4">수정</button>
                        </div>
                    </div>
                </div>
                {{-- 결제수단종류 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        결제수단종류</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="pay_req_act"></span>
                    </div>
                </div>
                {{-- 결제수단상세 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        결제수단상세</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="pay_card_name"></span>
                    </div>
                </div>
                {{-- 할부여부 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        할부여부</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="is_card_inst"></span>
                    </div>
                </div>
                {{-- 할부개월수 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        할부개월수</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="pay_card_inst"></span>
                    </div>
                </div>
                
                {{-- 결제메모 | 한줄 모두 차지 --}}
                <div class="col-12 row p-0 m-0">
                    <div class="col-2 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        결제메모</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="pay_memo"></span>
                        <div class="col text-end">
                            <button class="btn btn-sm btn-outline-secondary px-4 rounded-4">수정</button>
                        </div>
                    </div>
                </div>
                {{-- 카드 매출 전표 발행 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center flex-column">
                        카드 매출 전표 발행
                        <span class="fs-6 fw-light text-secondary">(*현금결제의 경우, 불가능함)</span>
                    </div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <button class="btn btn-sm btn-outline-secondary px-4 rounded-4 col">
                            카드사 매출 전표 발행하기
                        </button>
                    </div>
                </div>
                {{-- 현금영수중 발행 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center flex-column">
                        현금영수중 발행
                        <span class="fs-6 fw-light text-secondary">(*카드결제의 경우, 불가능함)</span>
                    </div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <button class="btn btn-sm btn-outline-secondary px-4 rounded-4 col">
                            국세청으로 데이터 전송하기
                        </button>
                    </div>
                </div>
            </div>


            <div class="row mt-4">
                <h5 class="row fw-bold">등록된 결제 수단 정보</h5>
            </div>

            <div class="row p-0 border">
                {{-- 카드사 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        카드사</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="card_"></span>
                        <div class="col text-end">
                            <button class="btn btn-sm btn-outline-secondary px-4 rounded-4">수정</button>
                        </div>
                    </div>
                </div>
                {{-- 카드번호 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        카드번호</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="card_"></span>
                        <div class="col text-end">
                            <button class="btn btn-sm btn-outline-secondary px-4 rounded-4">수정</button>
                        </div>
                    </div>
                </div>
                {{-- 유효기간 --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        유효기간</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="card_"></span>
                        <div class="col text-end">
                            <button class="btn btn-sm btn-outline-secondary px-4 rounded-4">수정</button>
                        </div>
                    </div>
                </div>
                {{-- CVC --}}
                <div class="col-6 row p-0 m-0">
                    <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                        CVC</div>
                    <div class="p-2 text-center col border d-flex align-items-center ">
                        <span class="card_"></span>
                        <div class="col text-end">
                            <button class="btn btn-sm btn-outline-secondary px-4 rounded-4">수정</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection