@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
대쉬보드
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<style>
    .checkbox input[type=checkbox]:checked+span {
        background-color: #DCDCDC;
        border-color: #DCDCDC;
        width: 24px;
        height: 24px;
    }

    [data-row].scale-bg-gray_01 div {
        color: #999999 !important;
    }

    .scale-text-gray_05 [data-img-event],
    .scale-text-gray_05 [data-img-notice] {
        filter: grayscale(100%)
    }

    [data-row-outline]:hover {
        border: 1px solid #222 !important;
        color: #222;
    }

    [data-bundle="board"] tr:hover {
        background-color: #f9f9f9;
    }

    .hover-act:hover {
        border: 1px solid #222 !important;
        color: #222 !important;
        cursor: pointer;
    }

    .top-tab.active .hover-act {
        border: 1px solid #222 !important;
        color: #222 !important;
        ;
    }
</style>
<div>
    <input type="hidden" data-main-teach-seq value="{{ $teach_seq }}">
    <input type="hidden" data-main-group-type2 value="{{ $group_type2 }}">
    <div class="sub-title">
        <h2 class="text-sb-42px">{{ session()->get('region_name') }}<span
                class="ht-make-title on text-r-20px py-2 px-3 ms-1">{{ session()->get('group_name') }}</span></h2>
    </div>

    <div class="setion-block">
        <div
            class="sh-title-wrap align-items-sm-center justify-content-sm-between justify-content-start flex-column flex-sm-row">
            <div class="right-text">
                <img src="{{ asset('images/calendar_chk_icon.svg') }}" width="32">
                <p class="text-sb-28px">오늘의 요약</p>
            </div>
            <div class="left-text">
                {{-- <p class="gray-color text-m-20px" data-today-date> 월요일 오후 3시 35분 기준</p> --}}
            </div>
        </div>

        <div class="row content-block row-gap-3">
            {{-- 총괄 / 팀장 --}}
            @if($group_type2 == 'general' || $group_type2 == 'leader')
            <div class="col-lg-3 col-sm-6 col-md-6 top-tab active scale-text-gray_05" data-type="new"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">신규 배정</b>입니다.</p>
                        </div>
                        <div>
                            <p class=" text-m-20px"><span class="text-sb-42px" data-main-top-cnt="new">0</span>명</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-md-6 top-tab scale-text-gray_05" data-type="counsel_yet"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">상담 예정 학생</b>입니다.</p>
                        </div>
                        <div>
                            <p class=" text-m-20px"><span class="text-sb-42px" data-main-top-cnt="counsel_yet">0</span>명
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 top-tab scale-text-gray_05" data-type="payment_yet"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">결제 대기</b>입니다.</p>
                        </div>
                        <div>
                            <p class="text-m-20px"><span class="text-sb-42px" data-main-top-cnt="payment_yet" 0</span>명
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 top-tab mb-2 scale-text-gray_05" data-type="payment"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">결제 완료</b>입니다.</p>
                        </div>
                        <div>
                            <p class=" text-m-20px"><span class="text-sb-42px" data-main-top-cnt="payment">0</span>명</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 관리 --}}
            @elseif($group_type2 == 'run' && $grop_type3??'' == '')
            <div class="col-lg-3 col-sm-6 col-md-6 top-tab mb-2 scale-text-gray_05" data-type="study_complete_per"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">학습 완료율</b>입니다.</p>
                        </div>
                        <div>
                            <p class=" text-m-20px"><span class="text-sb-42px"
                                    data-main-top-cnt="study_complete_per">0</span>명</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 top-tab scale-text-gray_05" data-type="counsel_yet"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">상담 예정 학생</b>입니다.</p>
                        </div>
                        <div>
                            <p class=" text-m-20px"><span class="text-sb-42px" data-main-top-cnt="counsel_yet">0</span>명
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 top-tab mb-2 scale-text-gray_05" data-type="payment"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">만료 임박 학생</b>입니다.</p>
                        </div>
                        <div>
                            <p class=" text-m-20px"><span class="text-sb-42px" data-main-top-cnt="payment">0</span>명</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 top-tab active scale-text-gray_05" data-type="new"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">신규 배정</b>입니다.</p>
                        </div>
                        <div>
                            <p class=" text-m-20px"><span class="text-sb-42px" data-main-top-cnt="new">0</span>명</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- 상담 --}}
            @esleif($group_type2 == 'run' && $grop_type3??'' == 'counsel')
            <div class="col-lg-3 col-sm-6 col-md-6 top-tab mb-2 scale-text-gray_05" data-type="new_counsel"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">신규 상담</b>입니다.</p>
                        </div>
                        <div>
                            <p class=" text-m-20px"><span class="text-sb-42px" data-main-top-cnt="new_counsel">0</span>명
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 top-tab scale-text-gray_05" data-type="payment_yet"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">결제 예정 학생</b>입니다.</p>
                        </div>
                        <div>
                            <p class="text-m-20px"><span class="text-sb-42px" data-main-top-cnt="payment_yet" 0</span>명
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 top-tab scale-text-gray_05" data-type="counsel_yet_all"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">상담 대기 전체</b>입니다.</p>
                        </div>
                        <div>
                            <p class="text-m-20px"><span class="text-sb-42px" data-main-top-cnt="counsel_yet_all"
                                    0</span>명</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 top-tab scale-text-gray_05" data-type="counsel_complete_month"
                onclick="dashBoardTopTab(this)">
                <div class="card-box h-100 px-4 py-3 mb-2 h-center hover-act">
                    <div class="d-flex align-items-center justify-content-between col">
                        <div class="flex-row lh-base ">
                            <p class="text-b-24px " data-today-date></p>
                            <p class="text-b-24px "><b class="">이번달 상담 완료</b>입니다.</p>
                        </div>
                        <div>
                            <p class="text-m-20px"><span class="text-sb-42px" data-main-top-cnt="counsel_complete_month"
                                    0</span>명</p>
                        </div>
                    </div>
                </div>
            </div>

            @endif

        </div>
    </div>

    {{-- 중단 --}}
    <div class="section-block row mx-0">
        <aside class="px-0 col-4">
            {{-- to do list --}}
            <div class="modal-shadow-style rounded p-4">
                <h3 class="h-center gap-2 p-4">
                    <img src="{{ asset('images/yellow_chk_icon.svg') }}" width="32">
                    <span class="text-sb-24px">To Do List</span>
                </h3>
                <div data-bundle="to_do_list">
                    <div data-row="copy" class="p-4" hidden>
                        <input type="hidden" data-todo-seq>
                        <div class="d-flex">
                            <div class="col ">
                                <div data-todo-content class="text-sb-20px mb-2">채용공고 게시</div>
                                <div data-todo-sub-content class="text-sb-20px text-danger">다음주까지</div>
                            </div>
                            <div class="col-auto h-center">
                                <label class="checkbox">
                                    <input type="checkbox" data-is-complete onchange="dashBoardToDoChk(this)">
                                    <span class="rounded-pill"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-center justify-content-end ms-4 mt-3">
                    <button data-btn class="btn h-center text-b-20px gap-3 p-2" onclick="dashBordToDoModalShow();">
                        <span>추가</span>
                        <img src="{{ asset('images/gray_cir_plus.svg') }}" width="32">
                    </button>
                </div>
            </div>
        </aside>
        <article class="px-0 col">
            {{-- //총괄 제네럴 --}}
            @if($group_type2 == 'general')
            <section>
                {{-- 팀별 카테고리 카운트 완료 --}}
                <div class="d-flex v-center ms-4 mb-4 pb-2">
                    <h3 class="text-sb-24px h-center gap-2  col">
                        <img src="{{ asset('images/yellow_card_icon.svg') }}" width="32">
                        {{-- 첫 화면은 신규배정으로. --}}
                        <span data-team-title-cnt>신규 배정</span>
                    </h3>
                    <span class="col-auto">
                        <button onclick="" class="btn h-center text-b-20px">
                            <span>더 보기</span>
                            <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="32">
                        </button>

                    </span>
                </div>

                <div data-bundle="team_payment" class="row mx-0 gx-4 ps-2 ms-1">
                    <div data-row="copy" class="col-3 scale-text-gray_05 cursor-pointer" hidden>
                        <div class="p-4 border rounded-4" data-row-outline>
                            <div data-team-name class="text-b-24px mb-3 text-center"></div>
                            <div class="text-center">
                                <span data-cnt class="text-sb-42px">2</span>
                                <span class="text-sb-18px">명</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                        dashBoardStOfTeamPaymentCnt();
                    });
                    // 팀별 결제 수치 가져오기.
                    function dashBoardStOfTeamPaymentCnt(){
                        const act_top_tab = document.querySelector('.top-tab.active');
                        const cnt_type = act_top_tab.dataset.type;
                        const cnt_types = []; cnt_types.push(cnt_type);
                        const teach_seq = document.querySelector('[data-main-teach-seq]').value;
                        const page = "/teacher/dashboard/team/cnt/select";
                        const parameter = {
                            teach_seq: teach_seq,
                            cnt_types: cnt_types
                        };
                        queryFetch(page, parameter, function(result){
                            if((result.resultCode||'') == 'success'){
                                // 초기화
                                const bundle = document.querySelector('[data-bundle="team_payment"]');
                                const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                                bundle.innerHTML = '';
                                bundle.appendChild(copy_row);

                                // 데이터 삽입
                                const total_cnts = result[cnt_type+'_cnts'];
                                total_cnts.forEach(total_cnt=> {
                                    const row = copy_row.cloneNode(true);
                                    row.hidden = false;
                                    row.setAttribute('data-row', 'clone');
                                    row.querySelector('[data-team-name]').innerText = total_cnt.team_name;
                                    row.querySelector(`[data-cnt]`).innerText = total_cnt[`${cnt_type}_cnt`] ?? 0;
                                    bundle.appendChild(row);
                                });
                            }else{}
                        });
                    }
            </script>

            @elseif($group_type2 == 'leader')
            <secion>
                {{-- 결제 완료 학생 리스트 --}}
                <table class="table-style w-100" style="min-width: 100%;">
                    <thead class="">
                        <tr class="text-sb-20px modal-shadow-style rounded">
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

            </secion>
            @endif
            {{-- 80px --}}
            <div class="py-lg-4"></div>
            <div class="py-lg-3"></div>

            <section>
                {{-- 공지사항, 이벤트 --}}
                <div class="d-flex v-center ms-4 mb-4 pb-2">
                    <h3 data-h3-board="notice" onclick="dashBoardBoardTab(this);"
                        class="text-sb-24px h-center gap-2  col-auto me-4 cursor-pointer active">
                        <img data-img-notice src="{{ asset('images/yellow_notice_icon.svg') }}" width="32">
                        공지사항
                    </h3>
                    <h3 data-h3-board="event" onclick="dashBoardBoardTab(this);"
                        class="text-sb-24px h-center gap-2  col ms-1 cursor-pointer scale-text-gray_05">
                        <img data-img-event src="{{ asset('images/bell_icon.svg') }}" width="32">
                        이벤트
                    </h3>
                    <span class="col-auto">
                        <button data-btn-more-board class="btn h-center text-b-20px"
                            onclick="dashBoardMovePage('notice')">
                            <span>더 보기</span>
                            <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="32">
                        </button>
                    </span>
                </div>
                <div class="ms-4">
                    <table class="w-100 table-style table-h-82">
                        <thead class="modal-shadow-style rounded">
                            <tr class="text-sb-20px ">
                                <th>구분</th>
                                <th>내용</th>
                                <th>등록일</th>
                            </tr>
                        </thead>
                        <tbody data-bundle="board">
                            <tr data-row="copy" onclick="dashBoardBoardClick(this)" class="text-m-20px cursor-pointer"
                                hidden>
                                <input type="hidden" data-board-seq>
                                <td data-gubun> </td>
                                <td data-content> </td>
                                <td data-created-at> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- 페이징 --}}
                <div class="all-center mt-52">
                    <div class="col-auto"></div>
                    <div class="col">
                        <div class="col d-flex justify-content-center">
                            <ul class="pagination col-auto" data-page="1" hidden>
                                <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1"
                                    onclick="userPaymentPageFunc('1', 'prev')">
                                    <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                                </button>
                                <li class="page-item" hidden>
                                    <a class="page-link" onclick="">0</a>
                                </li>
                                <span class="page" data-page-first="1" hidden
                                    onclick="userPaymentPageFunc('1', this.innerText);" disabled>0</span>
                                <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1"
                                    onclick="userPaymentPageFunc('1', 'next')" data-is-next="0">
                                    <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                                </button>
                            </ul>
                        </div>
                    </div>
                    <div class="col-auto">
                        {{-- <button onclick="" data-btn-write
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-3">작성하기</button>
                        --}}
                    </div>
                </div>

            </section>
        </article>
    </div>

    {{-- 160px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-4"></div>
    </div>
    {{-- 모달 / 게시판 상세보기. --}}
    <div class="modal fade " id="modal_div_board_detail" tabindex="-1" aria-labelledby="exampleModalLabel"
        style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog rounded modal-lg">
            <div class="modal-content border-none rounded p-3 modal-shadow-style">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5 text-b-24px" id="">
                        게시판 상세보기
                    </h1>
                    <button type="button" style="width:32px;height:32px" class="btn-close close-btn"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- :게시판 상세보기. --}}
                    @include('layout.board_detail')
                </div>
            </div>
        </div>
    </div>
    {{-- 모달 / 2개 입력창, 상단 content, 하단은 sub content --}}
    <div class="modal fade " id="modal_div_todolist_add" tabindex="-1" aria-labelledby="exampleModalLabel"
        style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog rounded modal-lg">
            <div class="modal-content border-none rounded p-3 modal-shadow-style">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5 text-b-24px" id="">
                        To Do List 추가
                    </h1>
                    <button type="button" style="width:32px;height:32px" class="btn-close close-btn"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" data-inp-todo-content="1"
                        class="search_str lg-search border-gray rounded rounded-3text-m-20px w-100" placeholder="메모 추가">
                    <input type="text" data-inp-todo-content="2"
                        class="search_str lg-search border-gray rounded rounded-3text-m-20px w-100" placeholder="강조" ">
          </div>
          <div class=" modal-footer border-top-0">
                    <div class="col ps-0">
                        <button type="button"
                            class="modal_close btn-lg-secondary text-sb-20px rounded scale-bg-gray_01 scale-text-gray_05 w-100 justify-content-center"
                            data-bs-dismiss="modal">닫기</button>
                    </div>
                    <div class="col ps-0">
                        <button type="button" onclick="dashBoardToDoListAdd()"
                            class="btn-lg-primary text-b-24px rounded scale-text-white w-100 text-center justify-content-center">
                            추가</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            const now_date = new Date().format('yy년 MsM월 dsd일');
            const today_date = document.querySelectorAll('[data-today-date]');
            today_date.forEach(el => {
                el.innerText = now_date;
            });

            //상단 카운트 불러오기
            dashBoardTopTabCntSelect();

            // To do list 불러오기
            dashBoardToDoList();

            // 게시판 불러오기.
            dashBoardBoardList();
        });

        // To Do List 불러오기.
        function dashBoardToDoList(){
            const teach_seq = document.querySelector('[data-main-teach-seq]').value;

            const page = "/teacher/dashboard/todolist/select";
            const parameter = {
                teach_seq: teach_seq
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    // 초기화
                    const bundle = document.querySelector('[data-bundle="to_do_list"]');
                    const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                    bundle.innerHTML = '';
                    bundle.appendChild(copy_row);

                    const todolists = result.todolists;
                    todolists.forEach(todo => {
                        const row = copy_row.cloneNode(true);
                        row.hidden = false;
                        row.setAttribute('data-row', 'clone');
                        row.querySelector('[data-todo-seq]').value = todo.id;
                        row.querySelector('[data-todo-content]').innerText = todo.todo_content;
                        row.querySelector('[data-todo-sub-content]').innerText = todo.todo_sub_content;
                        if(todo.is_complete == 'Y'){
                            row.querySelector('[data-is-complete]').checked = true;
                            row.classList.add('scale-bg-gray_01');
                        }else{

                        }
                        bundle.appendChild(row);
                    });
                }else{}
            })
        }

        // to do list 에서 is_complete(체크박스) 변경시.
        function dashBoardToDoChk(vthis){
            const todolist = vthis.closest('[data-row="clone"]');
            const todo_seq = todolist.querySelector('[data-todo-seq]').value;
            const checked = vthis.checked;
            if(checked){
                todolist.classList.add('scale-bg-gray_01');
            }else{
                todolist.classList.remove('scale-bg-gray_01');
            }
            dashBoardToDoUpdate(todo_seq, checked);
        }

        // to do list chage upadte
        function dashBoardToDoUpdate(todo_seq, checked){
            const teach_seq = document.querySelector('[data-main-teach-seq]').value;
            const page = "/teacher/dashboard/todolist/update";
            const parameter = {
                teach_seq: teach_seq,
                todo_seq: todo_seq,
                is_complete: checked ? 'Y' : 'N'
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    // 성공
                }else{
                    // 실패
                }
            });
        }


        // 공지사항 이벤트 탭 클릭.
        function dashBoardBoardTab(vthis){
            const h3s = document.querySelectorAll('[data-h3-board]');
            h3s.forEach(h3 => {
                h3.classList.remove('active');
                h3.classList.add('scale-text-gray_05');
            });
            vthis.classList.add('active');
            vthis.classList.remove('scale-text-gray_05');

            const board_name = vthis.getAttribute('data-h3-board');
            const btn_board_more = document.querySelector('[data-btn-more-board]');
            btn_board_more.setAttribute('onclick', `dashBoardMovePage('${board_name}');`);

            dashBoardBoardList();
        }
        // 공지사항, 이벤트 불러오기.
        function dashBoardBoardList(page_num){
            const type = document.querySelector('.active[data-h3-board]').getAttribute('data-h3-board');
            const board_name = type;
            const board_page_max = 4;
            const is_content = 'Y';

            const page = "/manage/board/select";
            const parameter = {
                board_name: board_name,
                page: page_num??1,
                board_page_max: board_page_max,
                is_content: is_content
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    // 초기화
                    const bundle = document.querySelector('[data-bundle="board"]');
                    const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                    bundle.innerHTML = '';
                    bundle.appendChild(copy_row);

                    const boards = result.board;
                    dashBoardTablePaging(result.board, '1');
                    boards.data.forEach(board => {
                        const row = copy_row.cloneNode(true);
                        row.hidden = false;
                        row.setAttribute('data-row', 'clone');
                        row.querySelector('[data-board-seq]').value = board.id;
                        // 오늘로부터 3일 전까지면 gubun은 NEW 아니면
                        if(board.created_at > new Date(new Date().getTime() - 3 * 24 * 60 * 60 * 1000)){
                            row.querySelector('[data-gubun]').innerText = 'NEW';
                            row.querySelector('[data-gubun]').classList.add('text-danger');
                        }else{
                            row.querySelector('[data-gubun]').innerText = board.category_name;
                        }
                        row.querySelector('[data-content]').innerHTML = board.content;
                        row.querySelector('[data-created-at]').innerText = (board.created_at||'').substr(2, 8).replace(/-/g, '.');
                        bundle.appendChild(row);
                    });
                }
            });
        }
    function dashBoardTablePaging(rData, target){
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

    //페이징 버튼 클릭시
    function userPaymentPageFunc(target, type){
            if(type == 'next'){
                const page_next = document.querySelector(`[data-page-next="${target}"]`);
                if(page_next.getAttribute("data-is-next") == '0') return;
                // data-page 의 마지막 page_num 의 innerText를 가져온다
                const last_page = document.querySelector(`[data-page="${target}"] .page_num:last-of-type`).innerText;
                const page = parseInt(last_page) + 1;
                if(target == "1")
                     dashBoardBoardList(page);
            }
            else if(type == 'prev'){
                // [data-page-first]  next tag 의 innerText를 가져온다
                const page_first = document.querySelector(`[data-page-first="${target}"]`);
                const page = page_first.innerText;
                if(page == 1) return;
                const page_num = page*1 -1;
                if(target == "1")
                     dashBoardBoardList(page);
            }
            else{
                if(target == "1")
                     dashBoardBoardList(type);
            }
    }

    // 페이지 이동
    function dashBoardMovePage(type){
        switch(type){
            case 'notice':
                location.href = "/manage/boardnotice";
            break;
            case 'event':
                location.href = "/student/event";
            break;
        }
    }

    // 공지사항 이벤트 클릭
    function dashBoardBoardClick(vthis){
        const board_seq = vthis.querySelector('[data-board-seq]').value;
        const board_name = document.querySelector('.active[data-h3-board]').getAttribute('data-h3-board');
        // data-btn-board-close 에 onclick = layoutBoardCloseDetail(callback) 넣어주기.
        // :게시판 상세보기 양식1
        const btn_detail_close = document.querySelector('[data-btn-board-close]');
        btn_detail_close.setAttribute('onclick', "dashBoardBoardClose();");
        const parameter = {
            board_seq: board_seq,
            board_name: board_name
        };
        layoutBoardDetail(parameter, function(){
            // 게시판 상세 페이지 열릴때
            const myModal = new bootstrap.Modal(document.getElementById('modal_div_board_detail'), {
                keyboard: false,
                backdrop: 'static'
            });
            myModal.show();
        });
    }
    function dashBoardBoardClose(){
        layoutBoardCloseDetail(function(){
            // 게시판 상세 페이지 닫힐때
           const modal = document.getElementById('modal_div_board_detail');
            modal.querySelector('.btn-close').click();
        });
    }

    // 상단 탭 클릭시.
    function dashBoardTopTab(vthis){
        // .top-tab 모두 비활성화
        const top_tabs = document.querySelectorAll('.top-tab');
        const group_type2 = document.querySelector('[data-main-group-type2]').value;
        top_tabs.forEach(tab => {
            tab.classList.remove('active');
        });

        vthis.classList.add('active');
        if(group_type2 == 'general'){
            dashBoardStOfTeamPaymentCnt();
        }else if(group_type2 == 'leader'){
            // 결제 완료 학생 리스트
            // 추가 코드
        }
    }

    // to do list 추가 모달 추가.
    function dashBordToDoModalShow(){
        const modal = document.getElementById('modal_div_todolist_add');
        const inps1 = modal.querySelectorAll('[data-inp-todo-content="1"]');
        const inps2 = modal.querySelectorAll('[data-inp-todo-content="2"]');
        //초기화 시켜주기
        inps1.value = '';
        inps2.value = '';

        const myModal = new bootstrap.Modal(document.getElementById('modal_div_todolist_add'), {
            keyboard: false,
            backdrop: 'static'
        });
        myModal.show();
    }
    // to do list 추가 버튼 클릭
    function dashBoardToDoListAdd(){
        const modal = document.getElementById('modal_div_todolist_add');
        const todo_content = modal.querySelector('[data-inp-todo-content="1"]').value;
        const todo_sub_content = modal.querySelector('[data-inp-todo-content="2"]').value;
        const teach_seq = document.querySelector('[data-main-teach-seq]').value;
        const page = "/teacher/dashboard/todolist/insert";

        const parameter = {
            teach_seq: teach_seq,
            todo_content: todo_content,
            todo_sub_content: todo_sub_content
        };

        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                // 성공
                dashBoardToDoList();
                modal.querySelector('.btn-close').click();
            }else{
                // 실패
            }
        });
    }

    // 상단 탭 카운트 불러오기.
    function dashBoardTopTabCntSelect(){
        const act_top_tab = document.querySelector('.top-tab.active');
        const cnt_type = act_top_tab.dataset.type;
        const cnt_types = [
            "new", "counsel_yet", "payment_yet", "payment"
        ];
        const teach_seq = document.querySelector('[data-main-teach-seq]').value;
        const base_type = 'all';

        const page = "/teacher/dashboard/team/cnt/select";
        const parameter = {
            teach_seq: teach_seq,
            cnt_types: cnt_types,
            base_type: base_type
        };
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') != ''){
                const main_cnt_new = document.querySelector('[data-main-top-cnt="new"]');
                const main_cnt_counsel_yet = document.querySelector('[data-main-top-cnt="counsel_yet"]');
                const main_cnt_payment_yet = document.querySelector('[data-main-top-cnt="payment_yet"]');
                const main_cnt_payment = document.querySelector('[data-main-top-cnt="payment"]');

                let new_cnt = 0;
                let counsel_yet_cnt = 0;
                let payment_yet_cnt = 0;
                let payment_cnt = 0;

                const new_cnts = result.new_cnts;
                const counsel_yet_cnts = result.counsel_yet_cnts;
                const payment_yet_cnts = result.payment_yet_cnts;
                const payment_cnts = result.payment_cnts;

                if(new_cnts.length > 0) new_cnts.forEach(db => { new_cnt += db.new_cnt; });
                counsel_yet_cnts.forEach(db => { counsel_yet_cnt += db.counsel_yet_cnt; });
                payment_yet_cnts.forEach(db => { payment_yet_cnt += db.payment_yet_cnt; });
                payment_cnts.forEach(db => { payment_cnt += db.payment_cnt; });

                main_cnt_new.innerText = new_cnt;
                main_cnt_counsel_yet.innerText = counsel_yet_cnt;
                main_cnt_payment_yet.innerText = payment_yet_cnt;
                main_cnt_payment.innerText = payment_cnt;
            }else{}
        });
    }
</script>
@endsection