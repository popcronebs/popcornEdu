@extends('layout.layout')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <div class="row ps-5 pe-3 pt-5">
        <div class="row mt-2">
            <div class="row">
                <div class="col-1 d-flex align-items-center">
                    통합검색
                </div>
                <div class="col-2">
                    <select class="form-select bg-light" id="teach_plist_sel_search_type">
                        <option value="">검색기준</option>
                        <option value="student_name">학생이름</option>
                        <option value="student_phone">휴대폰 번호</option>
                        <option value="grade">학년</option>
                    </select>
                </div>
                <div class="col">
                    <input id="teach_plist_inp_search_str" type="text" class="form-control bg-light" placeholder="검색어" onkeyup="if(event.keyCode == 13){}">
                </div>
                <button class="btn btn-primary col-1" id="teach_plist_btn_search" onclick="">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" hidden></span>
                    조회</button>
            </div>
        </div>

        <div class="row mt-4">
            <div class="row">
                <ul class="nav nav-tabs mt-2 text-center">
                    <li class="nav-item cursor-pointer col-auto">
                        <a id="teach_mess_a_receive " class="nav-link tab_menu active" onclick="teachPlistTabMenu(this) " type="all">
                            <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                            상담 대기 회원
                            (<span>0</span>)
                        </a>
                    </li>
                    <li class="nav-item cursor-pointer col-auto">
                        <a id="teach_mess_a_send" class="nav-link tab_menu" onclick="teachPlistTabMenu(this)" type="current">
                            <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                            결제 대기 회원
                        </a>
                    </li>
                    <li class="nav-item cursor-pointer col-auto">
                        <a id="teach_mess_a_send" class="nav-link tab_menu" onclick="teachPlistTabMenu(this)" type="current">
                            <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                            통합 결제/거래 기록
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="row col">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            {{-- checkbox, 주문번호, 대상회원, 상태, 최근결제일시, 결제금액, 상품명, 이용기간, 전여일수, 상담 --}}
                            <th onclick="event.stopPropagation();this.querySelector('input').click();">
                                <input type="checkbox" class="form-check-input" onclick="event.stopPropagation();teachPlistCheckAll(this)">
                            </th>
                            <th colspan="2">주문번호</th>
                            <th>대상회원</th>
                            <th>상태</th>
                            <th>최근결제일시</th>
                            <th>결제금액</th>
                            <th>상품명</th>
                            <th>이용기간</th>
                            <th>전여일수</th>
                            <th>상담</th>
                        </tr>
                    </thead>
                    <tbody id="teach_plist_tby_paylist">
                        <tr class="copy_tr_paylist">
                            <td onclick="event.stopPropagation();this.querySelector('input').click();">
                                <input type="checkbox" class="form-check-input" onclick="event.stopPropagation()">
                            </td>
                            <td data="#주문번호1"></td>
                            <td data="#주문번호2"></td>
                            <td data="#대상회원"></td>
                            <td data="#상태"></td>
                            <td data="#최근결제일시"></td>
                            <td data="#결제금액"></td>
                            <td data="#상품명"></td>
                            <td data="#이용기간"></td>
                            <td data="#전여일수"></td>
                            <td data="#상담">
                                <a href="javascript:void(0)" onclick="teachPlistDetailPageShow(this);">새창</a>
                            </td>
                            <input type="hidden" class="seq" value="1">
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // 탭메뉴 클릭시
        function teachPlistTabMenu(vthis){
            const type = vthis.getAttribute('type');
            const tab_menus = document.querySelectorAll('.tab_menu');
            tab_menus.forEach((tab_menu)=>{
                tab_menu.classList.remove('active');
            });
            vthis.classList.add('active');
            
            // [추가 코드] 
            // 이후 목록 조회 기능 추가.
        }
        
        //
        function teachPlistDetailPageShow(vthis){
            const tr = vthis.closest('tr');
            const seq = tr.querySelector('.seq').vsalue;
            const page = "/teacher/paylist/detail?order_seq="+seq
            const open = window.open(page);
            //window.open 의 title 을 #h6_title + '/ 상세내역'
            const title = document.querySelector('#h6_title').innerText;
            open.onload = function(){
                open.document.querySelector('title').innerText = title + '/ 상세내역';
                open.document.querySelector('#h6_title').innerText = title + '/ 상세내역';
            }


        }

    </script>
@endsection