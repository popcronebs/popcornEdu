@extends('layout.layout')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <div class="col-12 pe-3 ps-3 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        </div>

        {{-- 상단 조회 기능 --}}
        <div class="row gap-3 px-3">
            <input id="authority_inp_search_str" type="text" placeholder="검색어를 입력해주세요." class="input col"
                onkeyup="if(event.keyCode == 13) goodsSelect();">
            <button class="btn btn-outline-primary rounded col-1" onclick="goodsSelect();">조회</button>
        </div>

        {{-- 권한 등록 버튼 --}}
        <div class="row mt-2 mb-2 justify-content-between pe-3">
            <div class="d-inline-block" style="width:auto">
                <ul id="goods_ul_tab" class="nav nav-tabs cursor-pointer">
                    <li class="nav-item">
                        <a class="nav-link active" onclick="goodsTab(this);">이용권</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="goodsTab(this);">평가</a>
                    </li>
                    {{-- + --}}
                    <li class="nav-item">
                        <a class="nav-link" onclick="">+</a>
                    </li>
                </ul>
            </div>

            <button class="btn btn-primary float-end" style="width:auto" 
            data-bs-toggle="modal" data-bs-target="#goods_div_add_edit">신규 이용권 등록</button>
        </div>

        {{-- 권한?/그룹 리스트 table --}}
        <div class="row">
            <div class="col-12 tableFixedHead border-top border-bottom overflow-auto" style="height: calc(100vh - 240px)">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            {{-- 이용권명, 구분, 대상학년, 기간, 권한그룹, 콘텐츠관리, 금액, 자동결제, 사용활성화, 기능[상세btn, 삭제btn] --}}
                            <th>이용권명</th>
                            <th>구분</th>
                            <th>대상학년</th>
                            <th>기간</th>
                            <th>권한그룹</th>
                            <th>콘텐츠관리</th>
                            <th>금액</th>
                            <th>자동결제</th>
                            <th>사용활성화</th>
                            <th>기능</th>
                        </tr>
                    </thead>
                    <tbody id="goods_tby_list">
                        <tr class="copy_tr_goods" hidden>
                            <td class="goods_name " data="이용권명"></td>
                            <td class="" data="구분"></td>
                            <td class="goods_grade " data="대상학년"></td>
                            <td data="기간">
                                <div>
                                    <span class="goods_period "></span>
                                    <span>개월</span>
                                </div>
                            </td>
                            <td class="group_name " data="권한그룹"></td>
                            <td class="" data="콘텐츠관리">
                                <a href="">콘텐츠관리</a>
                            </td>
                            <td class="goods_price" data="금액"> </td>
                            <td data="자동결제">
                                <input class="is_auto_pay" type="checkbox">
                            </td>
                            <td data="사용활성화">
                                <input class="is_use" type="checkbox">
                            </td>
                            <td class="">
                                <button class="btn btn-outline-secondary btn-sm" 
                                data-bs-toggle="modal" data-bs-target="#goods_div_add_edit"
                                onclick="goodsEdit(this);">상세</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="goodsDelete(this);">삭제</button>
                            </td>
                            <input type="hidden" class="id">
                            <input type="hidden" class="remark">
                            <input type="hidden" class="group_seq">
                        </tr>
                    </tbody>
                </table>
                <div class="text-center" id="goods_div_list_none" hidden>
                    <span>조회된 내용이 없습니다.</span>
                </div>
            </div>
        </div>

        {{-- 등록 / 수정 window --}}
        {{-- 정중앙에 오도록 / 배경 흰색 --}}
        <div class="modal fade" id="goods_div_add_edit" tabindex="-1" aria-hidden="true" 
        style="display: none;--bs-modal-width:800px;">
            <div class="modal-dialog  modal-dialog-centered">
                <input type="hidden" class="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">신규 (*상품명) 등록 / 수정</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="goodsAddWindowClear();"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered align-middle ">
                            <tr>
                                <th class="table-light" style="width: 20%">*이용권명</th>
                                <td style="width:30%">
                                    <input type="text" class="goods_name input col border border-secondary w-100"
                                        placeholder="이용권명을 입력하세요."></td>
                                <th class="table-light" style="width: 20%">구분</th>
                                <td style="width:30%">
                                    <select class="hpx-30 col border border-secondary w-100">
                                        <option value="elementary">초등</option>
                                        <option value="middle">중등</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light">설명</th>
                                <td><input type="text" class="remark input col border border-secondary w-100"
                                        placeholder="설명을 입력해주세요."></td>
                                <th class="table-light">기간</th>
                                <td>
                                    <select class="goods_period hpx-30 col border border-secondary w-100">
                                        <option value="6">6개월</option>
                                        <option value="12">12개월</option>
                                    </select>
                            </tr>
                            <tr>
                                <th class="table-light">대상학년</th>
                                <td>
                                    {{-- label input 1~3학년 / 4~6학년 --}}
                                    <div>
                                        <label class="form-check-label" for="goods_inp_grade_1">1학년</label>
                                        <input class="form-check-input goods_grade" type="checkbox"
                                            value="1" id="goods_inp_grade_1">
                                        <label class="form-check-label" for="goods_inp_grade_2">2학년</label>
                                        <input class="form-check-input goods_grade" type="checkbox"
                                            value="2" id="goods_inp_grade_2">
                                        <label class="form-check-label" for="goods_inp_grade_3">3학년</label>
                                        <input class="form-check-input goods_grade" type="checkbox"
                                            value="3" id="goods_inp_grade_3">
                                    </div>
                                    <div>
                                        <label class="form-check-label" for="goods_inp_grade_4">4학년</label>
                                        <input class="form-check-input goods_grade" type="checkbox"
                                            value="4" id="goods_inp_grade_4">
                                        <label class="form-check-label" for="goods_inp_grade_5">5학년</label>
                                        <input class="form-check-input goods_grade" type="checkbox"
                                            value="5" id="goods_inp_grade_5">
                                        <label class="form-check-label" for="goods_inp_grade_6">6학년</label>
                                        <input class="form-check-input goods_grade" type="checkbox"
                                                value="6" id="goods_inp_grade_6">
                                    </div>
                                </td>
                                <th class="table-light">권한그룹</th>
                                <td>
                                    <select class="group_seq hpx-30 col border border-secondary w-100">
                                        <option value="">그룹선택</option>
                                        @foreach ($user_groups as $group)
                                            <option value="{{ $group['id'] }}">{{ $group['group_name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light">콘텐츠</th>
                                <td>
                                    
                                </td>
                                {{-- 금액 input 원 --}}
                                <th class="table-light">금액</th>
                                <td>
                                    <input type="text" class="goods_price input col border border-secondary"
                                        placeholder="금액을 입력해주세요." style="width:80%" onkeyup="setMoney(this);">
                                    (원)
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light">*사용여부</th>
                                <td>
                                    <input type="checkbox" class="is_use form-check-input" value="Y" id="goods_inp_is_use">
                                </td>
                                {{-- 자동결제 input checkbox --}}
                                <th class="table-light">자동결제</th>
                                <td>
                                    <input type="checkbox" class="is_auto_pay form-check-input" value="Y" id="goods_inp_auto_pay">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="goodsAddWindowClear();">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="goodsInsert();">등록 / 변경사항 저장</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        goodsSelect();
        // 이용권 조회
        function goodsSelect(){
            const search_str = document.querySelector('#authority_inp_search_str').value;
            const page = "/manage/goods/select";
            const parameter = {
                search_str: search_str
            };
            //로딩 시작
            queryFetch(page, parameter, function(result){
                //로딩 끝
                //초기화
                const goods_tby_list = document.querySelector('#goods_tby_list');
                const copy_tr_goods = goods_tby_list.querySelector('.copy_tr_goods').cloneNode(true);
                goods_tby_list.innerHTML = '';
                goods_tby_list.appendChild(copy_tr_goods);

                if(result.resultCode == 'success'){
                    
                    for(let i = 0; i < result.goods.length; i++){
                        const r_data = result.goods[i];
                        const tr = copy_tr_goods.cloneNode(true);
                        tr.hidden = false;
                        tr.classList.remove('copy_tr_goods');
                        tr.classList.add('tr_goods');

                        tr.querySelector('.goods_name').innerText = r_data.goods_name;
                        tr.querySelector('.goods_grade').innerText = (r_data.goods_grade||'').replace(/(\d+)\|(\d+)/g, '$1,$2').replace(/(\d+)\|(\d+)/g, '$1,$2').replace(/\|/g, '');
                        tr.querySelector('.goods_grade').setAttribute('data', r_data.goods_grade);
                        tr.querySelector('.goods_period').innerText = r_data.goods_period;
                        tr.querySelector('.group_seq').value = r_data.group_seq;
                        tr.querySelector('.group_name').innerText = r_data.group_name;
                        tr.querySelector('.goods_price').innerText = r_data.goods_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        tr.querySelector('.is_auto_pay').checked = r_data.is_auto_pay == 'Y' ? true : false;
                        tr.querySelector('.is_use').checked = r_data.is_use == 'Y' ? true : false;
                        tr.querySelector('.id').value = r_data.id; 
                        tr.querySelector('.remark').value = r_data.remark;
                        goods_tby_list.appendChild(tr);
                    }

                }

                //tr이 없으면 조회된 내용이 없습니다.
                document.querySelector('#goods_div_list_none').hidden = true;
                if(document.querySelector('#goods_tby_list').querySelectorAll('tr').length == 1){
                    document.querySelector('#goods_div_list_none').hidden = false;
                }
            });
        }
        // 이용권 등록
        function goodsInsert(){
            const goods_div_add_edit = document.querySelector('#goods_div_add_edit');
            const goods_name = goods_div_add_edit.querySelector('.goods_name').value;
            const remark = goods_div_add_edit.querySelector('.remark').value;
            const goods_period = goods_div_add_edit.querySelector('.goods_period').value;
            let goods_grade = "";
            // |1|2|3|4|5|6| 형태로 저장
            goods_div_add_edit.querySelectorAll('.goods_grade').forEach(checkbox => {
                if(checkbox.checked){ goods_grade += `|${checkbox.value}`; }
            });
            if(goods_grade.length > 0){ goods_grade += "|"; }
            const group_seq = goods_div_add_edit.querySelector('.group_seq').value;
            const goods_price = goods_div_add_edit.querySelector('.goods_price').value.replace(/,/g, '');
            const is_use = goods_div_add_edit.querySelector('.is_use').checked ? 'Y' : 'N';
            const is_auto_pay = goods_div_add_edit.querySelector('.is_auto_pay').checked ? 'Y' : 'N';
            const id = goods_div_add_edit.querySelector('.id').value;
            const main_code = document.querySelector('.main_category_type.active').getAttribute('data');
            // const goods_type = document.querySelector('#goods_ul_tab').querySelector('.active').innerText;

            const page = "/manage/goods/insert";
            const parameter = {
                main_code:main_code,
                goods_name: goods_name,
                remark: remark,
                goods_period: goods_period,
                goods_grade: goods_grade,
                group_seq: group_seq,
                goods_price: goods_price,
                is_use: is_use,
                is_auto_pay: is_auto_pay,
                id: id
            };
            queryFetch(page, parameter, function(result){
                if(result.resultCode == 'success'){
                    sAlert('',"등록 / 변경사항 저장 완료");
                    goodsSelect();
                    // goodsEditClose();
                }else{
                    sAlert('', "등록 / 변경사항 저장 실패");
                }
            });
        }

        // 이용권 등록 WINDOW clear
        function goodsAddWindowClear(){
            const goodsDivAddEdit = document.querySelector('#goods_div_add_edit');
            goodsDivAddEdit.querySelector('.id').value = '';
            goodsDivAddEdit.querySelector('.goods_name').value = '';
            goodsDivAddEdit.querySelector('.remark').value = '';
            goodsDivAddEdit.querySelector('.goods_period').value = '6';
            const goodsGradeCheckboxes = goodsDivAddEdit.querySelectorAll('.goods_grade');
            goodsGradeCheckboxes.forEach(checkbox => checkbox.checked = false);
            goodsDivAddEdit.querySelector('.group_seq').value = 'elementary';
            goodsDivAddEdit.querySelector('.goods_price').value = '';
            goodsDivAddEdit.querySelector('.is_use').checked = false;
            goodsDivAddEdit.querySelector('.is_auto_pay').checked = false;
        }

        // 이용권 삭제
        function goodsDelete(vthis){
            const tr = vthis.closest('tr');
            const id = tr.querySelector('.id').value;
            const page = "/manage/goods/delete";
            const parameter = {
                id: id
            };
            sAlert('삭제', '삭제하시겠습니까?', 2, function(){
                queryFetch(page, parameter, function(result){
                    if(result.resultCode == 'success'){
                        sAlert('',"삭제 완료");
                        goodsSelect();
                    }else{
                        sAlert('', "삭제 실패");
                    }
                });
            });
        }
        
        // 이용권 수정
        function goodsEdit(vthis){
            const tr = vthis.closest('tr');
            const id = tr.querySelector('.id').value;
            //tr의 정보를 가져와서 window에 넣어준다.

            const goods_div_add_edit = document.querySelector('#goods_div_add_edit');
            goods_div_add_edit.querySelector('.id').value = id;
            goods_div_add_edit.querySelector('.goods_name').value = tr.querySelector('.goods_name').innerText;
            goods_div_add_edit.querySelector('.remark').value = tr.querySelector('.remark').value;
            goods_div_add_edit.querySelector('.goods_period').value = tr.querySelector('.goods_period').innerText;
            const goodsGradeCheckboxes = goods_div_add_edit.querySelectorAll('.goods_grade');
            goodsGradeCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                if(tr.querySelector('.goods_grade').innerText.indexOf(checkbox.value) > -1){
                    checkbox.checked = true;
                }
            });
            goods_div_add_edit.querySelector('.group_seq').value = tr.querySelector('.group_seq').value;
            goods_div_add_edit.querySelector('.goods_price').value = tr.querySelector('.goods_price').innerText;
            goods_div_add_edit.querySelector('.is_use').checked = tr.querySelector('.is_use').checked;
            goods_div_add_edit.querySelector('.is_auto_pay').checked = tr.querySelector('.is_auto_pay').checked;

        }

        // 돈 표기
        function setMoney(vthis){
            vthis.value = vthis.value.replace(/[^0-9]/g, '').replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
        }

        // [추가 코드] 콘텐츠 관련 내용 필요. 선행작업 필요
        
    </script>
@endsection
