@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '학부모 결제관리')

@section('add_css_js')
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
@endsection

{{-- 학부모 결제관리  --}}

@section('layout_coutent')
<div class="col pe-3 ps-3 mb-3 row position-relative">
    <section>
        <div class="sub-title row mx-0 justify-content-between">
            <h2 class="text-sb-42px px-0 col h-center">
                <img src="{{ asset('images/card_icon.svg')}}" width="72">
                <span class="me-2">결제 및 이용권 관리</span>
            </h2>
        </div>
    </section>

    <article class="row">
        <aside class="col-3">
        <div class="shadow-sm-2 p-4">
                <div class="pt-2">
                    <button data-btn-aside-tab="paylist" onclick="ptPayAsideTab(this);" class="btn btn-outline-primary-y ctext-gc1 w-100 border-0 rounded-4 p-4 h-center cfs-5 active">
                        <img src="https://sdang.acaunion.com/images/window_memo_icon.svg" class="me-2">
                        결제 내역
                    </button>
                </div>
                <div class="pt-2">
                    <button data-btn-aside-tab="payedit" onclick="ptPayAsideTab(this)" class="btn btn-outline-primary-y ctext-gc1 w-100 border-0 rounded-4 h-center cfs-5 p-4">
                        <img src="https://sdang.acaunion.com/images/svg/pen.svg" alt="32" class="me-2">
                        결제 정보 수정
                    </button>
                </div>
            </div>
            <div class="mt-4">
                <button type="button" class="col w-100 modal_next btn btn-primary-y fs-4 py-3 rounded-3" onclick="">
                    <div class="sp_loding spinner-border text-light spinner-border-sm align-middle mb-1 me-2" role="status" hidden=""></div>
                    결제하기
                </button>
            </div>
        </aside>
        {{--  결제내역 --}}
        <section class="col" data-payment-list>
            <div>
                {{-- 기간설정  --}}
                <div>
                    <div class="h-center justify-content-end">
                        <label class="d-inline-block select-wrap select-icon h-62">
                            <select id="select2" onchange="ptPaySelectDateType(this, '[data-search-start-date]','[data-search-end-date]');teachClGoodsListCounselSelect();"
                                class="date-change rounded-pill ps-4 border-gray sm-select text-sb-20px me-2 h-62">
                                <option value="">기간설정</option>
                                <option value="-1">오늘로보기</option>
                                <option value="0">1주일전</option>
                                <option value="1">1개월전</option>
                                <option value="2">3개월전</option>
                            </select>
                        </label>
                        <div class="h-center p-3 border rounded-pill">
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
                                <input type="date" style="width: 80px;height: 0.5px;" data-search-start-date
                                    oninput="teachClGoodsDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                            </div>
                            ~
                            <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start" style="height: 20px;">
                                <div class="h-center justify-content-between">
                                    <div data-date onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()" type="text"
                                        class="text-m-20px text-start scale-text-gray_05" readonly="" placeholder="">
                                        {{-- 상담시작일시 --}}
                                        {{ date('Y.m.d') }}
                                    </div>
                                    <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                                </div>
                                <input type="date" style="width: 80px;height: 0.5px;" data-search-end-date
                                    oninput="teachClGoodsDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                            </div>

                        </div>
                    </div>
                </div>
                {{-- 테이블 --}}
                <div class="mt-4 pt-2">
                    <table class="table-style w-100" style="min-width: 100%;">
                        <colgroup>
                            <col style="width: 80px;">
                        </colgroup>
                        <thead>
                            <tr class="text-sb-20px modal-shadow-style rounded">
                                <th>결제일자</th>
                                <th>상품</th>
                                <th>이용기간</th>
                                <th>결제금액</th>
                                <th>결제수단</th>
                            </tr>
                        </thead>
                        <tbody data-bundle="tby_pt_list">
                            <tr class="text-m-20px" data-row="copy" hidden>
                                <input type="hidden" data-student-seq="">
                                <td class=" py-4 text-black h-104">
                                    <span data-payment-date class=""> </span>
                                </td>
                                <td>
                                    <span data-goods-name> </span>
                                </td>
                                <td>
                                    <div data-goods-start-end-date> </div>
                                    <div class="text-primary" hidden>
                                        (<span data-goods-remain-date> </span>)
                                    </div>
                                </td>
                                <td>
                                    <span data-amount>0</span>원
                                </td>
                                <td>
                                    <div>
                                      <span data-req-act> </span> /
                                      <span data-card-inst> </span>
                                    </div>
                                    <div>
                                        <span data-card-nmae> </span>
                                        (<span data-accept-no> </span>)
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        {{--  결제 정보 수정 --}}
        <section class="col" data-payment-edit hidden>
            <div>
                {{--  결제 수단 등록 정보 --}}
                <div data-bundle="payment_method_information_list">
                    <div data-row="copy" >
                        <div class="modal-shadow-style rounded p-4 row">
                            <div class="px-0 col-auto">
                                <img src="{{asset('images/payment/toss.svg')}}?1" width="142">
                            </div>
                            <div class="px-0 col text-black text-sb-20px w-center flex-column ps-4">
                                <div>토스뱅크카드</div>
                                <div class="mt-2">
                                    <span class="scale-text-gray_05">**** **** **** 2323</span>
                                    <img src="{{asset('images/bar_icon')}}" width="2" height="12">
                                    <span > 12/30 ****</span>
                                </div>
                            </div>
                            <div class="px-0 col-auto text-sb-18px scale-text-gray_05 gap-2 h-center">
                                <button class="btn rounded-pill scale-bg-gray_01 px-4 scale-text-gray_05">수정하기</button>
                                <button class="btn rounded-pill scale-bg-gray_02 px-4 scale-text-gray_05">삭제하기</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </article>
</div>

<div data-explain="160">
    <div class="py-lg-5"> </div>
    <div class="py-lg-4"> </div>
    <div class="pt-lg-3"> </div>
</div>

<input type="hidden" data-today value="{{ date('Y-m-d') }}">
<script>

document.addEventListener('DOMContentLoaded', function() {
    ptPayPaymentSelect(paymentlist);
});

// 탭 변경.
function ptPayAsideTab(vthis){
    //data-btn-aside-tab 비활성화
    document.querySelectorAll('[data-btn-aside-tab]').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');
    const type = vthis.dataset.btnAsideTab;
    const section_paylist = document.querySelector('[data-payment-list]');
    const section_payedit = document.querySelector('[data-payment-edit]');
    if(type == 'paylist'){
        section_paylist.hidden = false;
        section_payedit.hidden = true;

    }else if(type == 'payedit'){
        section_paylist.hidden = true;
        section_payedit.hidden = false;
    }
}

// 기간설정 select onchange
function ptPaySelectDateType(vthis, start_date_tag, end_date_tag) {
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
        const today = document.querySelector('[data-today]').value;

        inp_start.value = today;
        inp_end.value = today;
    }
    // onchage()
    // onchange 이벤트가 있으면 실행
    if (inp_start.oninput)
        inp_start.oninput();
    if (inp_end.oninput)
        inp_end.oninput();
}

var paymentlist = [
    {
    "id": 1,
    "main_code": '',
    "team_code": "A00006",
    "student_type": "new",
    "student_seq": 2714,
    "is_regular": "N",
    "regular_date": '',
    "accept_no": '00123456',
    "goods_seq": 1,
    "goods_detail_seq": 1,
    "goods_name": "초등 베이직_일시납",
    "amount": 350000,
    "card_inst": 4,
    "card_name": "삼성카드",
    "card_no": "12123123",
    "req_act": 'card',
    "req_time": '',
    "created_id": 5,
    "ret_code": '',
    "ret_msg": '',
    "tax_id": '',
    "tax_type": '',
    "van_name": '',
    "van_no": '',
    "status_str": "결제완료",
    "status_no": 1,
    "sale_type": '',
    "sale": '',
    "dev_port": '',
    "dev_bussiness": '',
    "payment_due_date": "2024-06-05 16:35:46",
    "payment_date": "2024-06-17 15:28:42",
    "cancel_date": '',
    "payment_memo": "update",
    "pt_seq": '',
    "created_at": "2024-05-13 16:35:39",
    "updated_at": "2024-05-15 15:14:10",
    "goods_start_date":"2024-05-15 15:14:10",
    "goods_end_date":"2024-07-15 15:14:10",
    }
];

function ptPayPaymentSelect(data){
    // 초기화
    const bundle = document.querySelector('[data-bundle="tby_pt_list"]');
    const row_copy = document.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);

    data.forEach(function(d){
        const row = row_copy.cloneNode(true);
        row.hidden = false;
        row.querySelector('[data-payment-date]').innerText = (d.payment_date||'').substr(2, 14).replace(/-/gi, '.');
        row.querySelector('[data-goods-name]').innerText = d.goods_name;
        row.querySelector('[data-goods-start-end-date]').innerText =
            (d.goods_start_date||'').substr(2, 8).replace(/-/gi, '.') +'-' + (d.goods_end_date||'').substr(2, 8).replace(/-/gi, '.');
        row.querySelector('[data-amount]').innerText = (d.amount*1).toLocaleString();
        row.querySelector('[data-req-act]').innerText = ptPayGetReqAct(d.req_act);
        row.querySelector('[data-card-inst]').innerText = d.card_inst ? '할부 / '+d.card_inst+'개월' : '';
        row.querySelector('[data-card-nmae]').innerText = d.card_name;
        row.querySelector('[data-accept-no]').innerText = d.accept_no;
        bundle.appendChild(row);
    });
}

function ptPayGetReqAct(req_act){
    switch(req_act){
        case 'card':
            return '카드';
    }
}
</script>

@endsection
