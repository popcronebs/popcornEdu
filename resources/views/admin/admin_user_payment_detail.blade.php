@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
사용자 결제 관리
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<div class="row pt-2" data-div-main="user_add">
    <input type="hidden" data-main-payment-seq value="{{ $payment_seq }}">
    <input type="hidden" data-main-student-seq value="{{ $student_seq}}">

    <div class="sub-title d-flex justify-content-between">
        <h2 class="text-sb-42px">
            <button data-btn-back-page="" class="btn p-0 row mx-0 all-center" onclick="uPayDetailBack();">
                <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
            </button>
            <span class="me-2">결제상세정보</span>
        </h2>
    </div>


    <p class="text-sb-28px mb-4">상세정보</p>
    <table class="w-100 table-list-style table-border-xless table-h-92">
        <colgroup>
            <col style="width: 15%;">
            <col style="width: 35%;">
            <col style="width: 15%;">
            <col style="width: 35%;">

        </colgroup>
        <thead></thead>
        <tbody>
            <tr>
                <td class="text-start px-4">소속</td>
                <td class="text-start px-4">{{ $payment->region_name }}</td>
                <td class="text-start px-4">팀/관리선생님</td>
                <td class="text-start px-4">{{ $payment->team_name }} / {{ $payment->teach_name }} 선생님</td>
            </tr>
            <tr>
                <td class="text-start px-4">학생 이름(아이디)</td>
                <td class="text-start px-4">
                <span data-student-name>{{ $payment->student_name }}</span> (<span data-student-id>{{ $payment->student_id }}</span>)</td>
                <td class="text-start px-4">학부모 이름(아이디)</td>
                <td class="text-start px-4">{{ $payment->parent_name }} ({{ $payment->parent_id }})</td>
            </tr>
            <tr>
                <td class="text-start px-4">승인번호</td>
                <td class="text-start px-4">{{ $payment->accept_no }}</td>
                <td class="text-start px-4">결제상태</td>
                <td class="text-start px-4">
                    <div class="d-flex justify-content-between">
                        <p>{{ $payment->status_str }}</p>
                        @if($payment->status_no == 1)
                        <button type="button"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">환불처리</button>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-start px-4">결제일시</td>
                <td class="text-start px-4">{{ $payment->payment_date }}</td>
                <td class="text-start px-4">결제금액</td>
                <td class="text-start px-4">{{ number_format($payment->amount) }} 원</td>
            </tr>
            <tr>
                <td class="text-start px-4">이용권</td>
                <td colspan="3" class="text-start px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <span data-student-type="main" data-text="#신규등"
                                class="rounded studyColor-bg-studyComplete scale-text-white text-sb-20px px-4 py-1 me-2"></span>
                            <p class="text-sb-24px scale-text-black">{{ $payment->goods_due_name}}</p>

                            {{-- 상품권 정보 --}}
                            <input type="hidden" data-goods-seq="main" data-value="{{ $payment->goods_seq }}" value="{{ $payment->goods_seq }}"">
                            <input type="hidden" data-is-regular value="{{ $payment->is_regular }}">

                        </div>
                        <button type="button" onclick="uPayDetailModalShow();"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">이용권
                            변경하기</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-start px-4">이용기간(시작)</td>
                <td class="text-start px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <p data-goods-due-start-date data-text="{{ $payment->goods_due_start_date }}">
                            {{ \Carbon\Carbon::parse($payment->goods_due_start_date)->format('Y.m.d') }}
                        </p>
                        <button type="button" onclick="uPayDetailGoodsUpdate('start')"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">수정하기</button>
                    </div>
                </td>
                <td class="text-start px-4">이용기간(종료)</td>
                <td class="text-start px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <p data-goods-due-end-date data-text="{{ $payment->goods_due_end_date }}">
                            {{ \Carbon\Carbon::parse($payment->goods_due_end_date)->format('Y.m.d') }}
                        </p>
                        <button type="button" onclick="uPayDetailGoodsUpdate('end')"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">수정하기</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-start px-4">결제수단종류</td>
                <td class="text-start px-4" data-req-act="{{ $payment->req_act }}"></td>
                <td class="text-start px-4">결제수단상세</td>
                <td class="text-start px-4">
                    {{ $payment->card_name ?? '' }}
                </td>
            </tr>
            <tr>
                <td class="text-start px-4">할부여부</td>
                <td class="text-start px-4">{{ ($payment->card_inst*1 > 1) ? $payment->card_inst.'할부' : '일시불' }}</td>
                <td class="text-start px-4">할부 개월수</td>
                <td class="text-start px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <p>{{ ($payment->card_inst*1 > 1) ? $payment->card_inst.'개월' : '일시불' }}</p>
                        {{-- 10은 없는 수이므로 일단은 안나오게. --}}
                        @if($payment->status_no == 10)
                        <button type="button" onclick="uPayDetailGoodsUpdate('inst')"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">수정하기</button>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-start px-4">결제 메모</td>
                <td colspan="3" class="text-start px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <p data-payment-memo >{{ $payment->payment_memo }}</p>
                        <button type="button" onclick="uPayDetailGoodsUpdate('memo')"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">수정하기</button>
                    </div>
                    
                </td>
            </tr>
            <tr>
                <td class="text-start px-4">카드매출전표</td>
                <td colspan="3" class="text-start px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <p class="text-sb-24px studyColor-text-studyComplete">※ 현금결제의 경우 불가능합니다.</p>
                        </div>
                        <button type="button"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">카드사
                            매출전표 발행하기</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center mt-120 mb-32">
        <p class="text-sb-28px">이전 결제내역</p>
        <div class="h-center">
            <label class="d-inline-block select-wrap select-icon h-62">
                <select id="select2"
                    onchange="uPayDetailSelectDateType(this, '[data-search-start-date]','[data-search-end-date]');"
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
                        <div data-date onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                            type="text" class="text-m-20px text-start scale-text-gray_05" readonly="" placeholder="">
                            {{-- 상담시작일시 --}}
                            {{ date('Y.m.d') }}
                        </div>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                    </div>
                    <input type="date" style="width: 80px;height: 0.5px;" data-search-start-date
                        oninput="uPayDetailDateTimeSel(this)" value="{{ date('Y-m-d') }}">
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
                        oninput="uPayDetailDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                </div>

            </div>
        </div>
    </div>

    <table class="w-100 table-list-style table-border-xless table-h-92">
        <colgroup>
            <col style="width: 10%;">
        </colgroup>
        <thead></thead>
        <tbody data-bundle="tby_payment_history">
            <tr class="" data-row="copy1" hidden>
                <td>승인번호</td>
                <td colspan="5" class="px-4">
                    <p class="scale-text-black text-sb-24px d-flex justify-content-between">
                        <span data-accept-no></span>
                        <button class="btn p-0" onclick="uPayDetailHistoryOpen(this)">
                            <img src="{{ asset('images/dropdown_arrow_up.svg') }}" width="32">
                        </button>
                    </p>
                </td>
                <input type="hidden" data-payment-seq>
            </tr>
            <tr class="scale-bg-gray_01" data-row="copy2" hidden>
                <td>결제상태</td>
                <td class="px-4">
                    <div class="d-flex justify-content-between">
                        <p class="text-sb-24px " data-status-str data-text="#결제완료/승인"> </p>
                    </div>
                </td>
                <td>
                    <p class="text-sb-24px scale-text-gray_06">결제일시</p>
                </td>
                <td class="px-4">
                    <div class="d-flex justify-content-between">
                        <p class="text-sb-24px " data-payment-date data-text="#2023.07.14 17:23"></p>
                    </div>
                </td>
                <td>
                    <p class="text-sb-24px scale-text-gray_06">결제금액</p>
                </td>
                <td>
                    <p class="text-sb-24px" data-payment-amount data-text="#500,000원"></p>
                </td>
            </tr>
        </tbody>
    </table>
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
                      <div class="col-6" onclick="uPayDetailModalGoodsSel(this);" data-goods-row="{{ $good->id }}" class="cursor-pointer">
                        <div class="px-4 py-3 rounded-3 scale-text-gray_05 scale-text-white-hover primary-bg-mian-hover border-gray border-gray-hover-less">
                          <p class="text-sb-24px mb-12">{{ number_format($good->goods_price) }}원</p>
                          <p class="text-m-20px">{{ $good->goods_name }}({{ $good->is_auto_pay == 'Y' ? '월납':'완납' }})</p>
                        </div>
                        <input type="hidden" data-goods-seq value="{{ $good->id}}" >
                        <input type="hidden" data-goods-price value="{{ $good->goods_perice }}" >
                        <input type="hidden" data-goods-is-auto-pay="1" value="{{ $good->is_auto_pay }}" >
                        <input type="hidden" data-goods-goods_period value="{{ $good->goods_period }}" >
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
                    oninput="uPayDetailDateTimeSel(this)" value="{{ date('Y-m-d') }}">
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
</div>
<script>
    const payment_data = @json($payment);
    document.addEventListener('DOMContentLoaded', function() {
        uPayDetailHistorySelect();
        uPayDetailGoodsGetTypeDetail();
    });
    // 뒤로가기
    function uPayDetailBack(){
        sessionStorage.setItem('isBackNavigation', 'true');
        window.history.back();
    }
    
    // 이전 결제내역에서 오픈 버튼 클릭.
    function uPayDetailHistoryOpen(vthis){
        const tr = vthis.closest('tr');
        //next tr 보이게. vthis 180도 회전 
        tr.nextElementSibling.hidden = !tr.nextElementSibling.hidden;
        vthis.classList.toggle('rotate-180');
    }
    

    // 기간설정 선택시
    function uPayDetailSelectDateType(vthis, start_date_tag, end_date_tag) {
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
    function uPayDetailDateTimeSel(vthis){
        //datetime-local format yyyy.MM.dd HH:mm 변경
        const date = new Date(vthis.value);
        vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = date.format('yyyy.MM.dd')
            
        uPayDetailHistorySelect();
    }

    // 이전 결제 내역 조회
    function uPayDetailHistorySelect(){
        const payment_seq = document.querySelector('[data-main-payment-seq]').value;
        const student_seq = document.querySelector('[data-main-student-seq]').value;
        const start_date = document.querySelector('[data-search-start-date]').value;
        const end_date = document.querySelector('[data-search-end-date]').value;

        //결제내역 조회
        const page = "/manage/user/payment/detail/history/select";
        const parameter = {
            payment_seq: payment_seq,
            student_seq: student_seq,
            start_date: start_date,
            end_date: end_date
        };
        queryFetch(page, parameter, function(result){
            //초기화
            const bundle = document.querySelector('[data-bundle="tby_payment_history"]');
            const copy1_row = bundle.querySelector('[data-row="copy1"]').cloneNode(true);
            const copy2_row = bundle.querySelector('[data-row="copy2"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(copy1_row);
            bundle.appendChild(copy2_row);

            //결제내역
            result.payments.some(function(payment){
                const copy1 = copy1_row.cloneNode(true);
                const copy2 = copy2_row.cloneNode(true);
                copy1.hidden = false;
                copy1.setAttribute('data-row', 'clone1');
                copy2.setAttribute('data-row', 'clone2');

                copy1.querySelector('[data-accept-no]').innerText = payment.accept_no;
                copy1.querySelector('[data-payment-seq]').value = payment.payment_seq;
                copy2.querySelector('[data-status-str]').innerText = payment.status_str;
                copy2.querySelector('[data-payment-date]').innerText = (payment.payment_date||'').substr(0,16).replace(/-/g, '.');
                // 1000원 , 표기
                copy2.querySelector('[data-payment-amount]').innerText = ((payment.amount||0)*1).toLocaleString() + '원';
                bundle.appendChild(copy1);
                bundle.appendChild(copy2);
            });
        });
    }

    // 결제하기 버튼 클릭시 모달 오픈.
    function uPayDetailModalShow(){
       //user_payment_modal
        const modal = document.getElementById('user_payment_modal');
        //정보 가져오기.
        const student_name = document.querySelector('[data-student-name]').innerText;
        const student_type = document.querySelector('[data-student-type="main"]').innerText;
        const goods_due_start_date = document.querySelector('[data-goods-due-start-date]').getAttribute('data-text');
        const goods_due_end_date = document.querySelector('[data-goods-due-end-date]').getAttribute('data-text');
        const is_regular = document.querySelector('[data-is-regular]').value;
        const regular_date = document.querySelector('[data-regular-date]').value;
        const goods_seq = document.querySelector('[data-goods-seq="main"]').value;

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

        const myModal = new bootstrap.Modal(document.getElementById('user_payment_modal'), {
            keyboard: false,
            backdrop: 'static'
        });
        myModal.show(); 
    }

    // 학생의 이용권 상태 확인.
    function uPayDetailGoodsGetTypeDetail(){
        const tag = document.querySelector('[data-student-type="main"]');
        const data = payment_data;

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

    // 결제 모달에서 이용권(상품)선택시.
    function uPayDetailModalGoodsSel(vthis){
        const modal = document.querySelector('#user_payment_modal');
        //data-goods-row 모두 비활성화
        modal.querySelectorAll('[data-goods-row]').forEach(function(row){
            row.classList.remove('active');
        });
        vthis.classList.add('active');
    }
    
    // 이용기간 수정 / 시작일, 종료일
    function uPayDetailGoodsUpdate(type){
        if(type == 'start'){
            const goods_due_start_date_el = document.querySelector('[data-goods-due-start-date]');
            const start_date = goods_due_start_date_el.getAttribute('data-text');
            const msg = 
            `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">변경할 이용기간(시작)을 선택후 확인을 변경 버튼을 눌러주세요.</p>
                <p class="modal-title text-center text-sb-24px alert-bottom-m studyColor-text-studyComplete" id="">
                    <input type="date" class="text-center text-sb-24px" data-update-start-date value="${start_date}">
                </p>
            </div>
            `;
            sAlert('', msg, 3, function(){
                const start_date = document.querySelector('[data-update-start-date]').value;
                uPayDetailUpdateGoodsData('goods_start_date', start_date, function(){
                    goods_due_start_date_el.innerText = start_date.replace(/-/g, '.');
                    goods_due_start_date_el.setAttribute('data-text', start_date);
                });
            });
        }
        else if(type == 'end'){
            const goods_due_end_date_el = document.querySelector('[data-goods-due-end-date]');
            const end_date = goods_due_end_date_el.getAttribute('data-text');
            const msg = 
            `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">변경할 이용기간(종료)을 선택후 확인을 변경 버튼을 눌러주세요.</p>
                <p class="modal-title text-center text-sb-24px alert-bottom-m studyColor-text-studyComplete" id="">
                    <input type="date" class="text-center text-sb-24px" data-update-end-date value="${end_date}">
                </p>
            </div>
            `;
            sAlert('', msg, 3, function(){
                const end_date = document.querySelector('[data-update-end-date]').value;
                uPayDetailUpdateGoodsData('goods_end_date', end_date, function(){
                    goods_due_end_date_el.innerText = end_date.replace(/-/g, '.');
                    goods_due_end_date_el.setAttribute('data-text', end_date);
                });
            });
        }
        else if(type == 'memo'){
            const goods_memo_el = document.querySelector('[data-payment-memo]');
            const memo = goods_memo_el.innerText;
            const msg = 
            `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">변경할 메모를 입력후 확인을 변경 버튼을 눌러주세요.</p>
                <p class="modal-title text-center text-sb-24px alert-bottom-m studyColor-text-studyComplete" id="">
                    <textarea type="text" class="text-center text-sb-24px" data-update-memo>${memo}</textarea>
                </p>
            </div>
            `;
            sAlert('', msg, 3, function(){
                const memo = document.querySelector('[data-update-memo]').value;
                uPayDetailUpdateGoodsData('payment_memo', memo, function(){
                    goods_memo_el.innerText = memo;
                    goods_memo_el.setAttribute('data-text', memo);
                });
            });
        }
        else if(type == 'inst'){
            cnost 
        }
    }

    // 간단 결제 정보 업데이트.
    function uPayDetailUpdateGoodsData(type, data, callback){
        const payment_seq = document.querySelector('[data-main-payment-seq]').value;
        const page = "/manage/user/payment/detail/part/update";
        const parameter = {
            payment_seq: payment_seq,
            type: type,
            data: data
        };

        // 확인을 누르시면 수정 및 저장됩니다.
        const msg = 
        `
        <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
            <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">변경사항을 저장하시겠습니까?</p>
        </div>
        `;
        setTimeout(() => {
            sAlert('', msg, 3, function(){
                queryFetch(page, parameter, function(result){
                    if((result.resultCode||'') == 'success'){
                        toast('수정되었습니다.');
                        if(callback != undefined){
                            callback();
                        }
                    }
                });
            });
        }, 200);
    }
    
</script>
@endsection