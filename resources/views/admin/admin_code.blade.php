@extends('layout.layout')

{{-- INFO --}}
{{-- DB::codes 에서 code_step = 0 이 첫화면 리스트로 가져온다. 이 데이터들은 고정적으로 존재해야 시스템상 이상이 없음.  --}}

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <div class="col-12 pe-3 ps-3 position-relative">
        {{-- 상단 조회 기능 --}}
        <div class="row px-3">
            <div class="row col gap-2">
                <button class="btn btn-outline-success rounded col-auto" onclick="codeConnectInit();">강좌분류연결</button>
                <input id="code_inp_search_str" type="text" placeholder="검색어를 입력해주세요." class="input col" onkeyup="if(event.keyCode == 13) codeSelect('category');">
                <button class="btn btn-outline-secondary rounded col-auto" onclick="codeSelect('category');">조회</button>

                {{-- <button class="btn btn-outline-primary rounded col-auto" data-bs-toggle="modal"
                    data-bs-target="#code_div_add_edit">분류추가</button> --}}
            </div>
        </div>

        {{-- 분류? 공통코드 테이블 리스트 --}}
        <div class="mt-3 tableFixedHead overflow-auto border" style="height: calc(100vh - 190px)">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th style="width:140px;">분류</th>
                        <th style="width:80px;">MAX</th>
                        <th>하위 분류</th>
                        <th style="width:150px;">기능</th>
                    </tr>
                </thead>
                <tbody id="code_tby">
                    <tr class="copy_tr_code text-center align-middle">
                        <td data="분류" class="code_name">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="max_step text-center">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td data="하위 분류" class="code_names">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="text-center">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                            <div class="btn_function" hidden>
                                <button class="btn btn-outline-primary rounded btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#code_div_add_edit" onclick="codeDetailSettingModal(this);">상세</button>
                            </div>
                        </td>
                        <input type="hidden" class="code_seq">
                        <input type="hidden" class="code_category">
                    </tr>
                    {{-- 강좌 --}}
                    {{-- 평가분류 --}}
                    {{-- 사용자소속분류 --}}
                    {{-- 게시판분류 --}}
                    {{-- 상담분류 --}}
                    {{-- 수업분류 --}}
                    {{-- 메시지분류 --}}
                    {{-- 연결분류 --}}
                    {{-- 컨텐츠유형분류 --}}
                    {{-- 공개범위분류 --}}
                    {{-- 기간분류 --}}
                    {{-- 관심강좌분류 --}}
                    {{-- 학년분류 --}}
                    {{-- 학기분류 --}}
                    {{-- 수준분류 --}}
                    {{-- 사용자유형분류 --}}
                    {{-- 결제분류 --}}
                    {{-- 학습일지분류 --}}
                    {{-- 자녀알림분류 --}}
                    {{-- 강의상태분류 --}}
                    {{-- 강좌상태분류 --}}
                    {{-- 학습상태분류 --}}
                    {{-- 이용권상태분류 --}}
                    {{-- 사용자상태분류 --}}
                    {{-- 결제요청상태분류 --}}
                    {{-- 쪽지상태분류 --}}
                    {{-- 상담상태분류 --}}
                    {{-- 발송상태분류 --}}
                    {{-- 출석상태분류 --}}
                </tbody>
            </table>
            {{-- 내역이 없습니다. --}}
            <div class="text-center" id="code_tby_none" hidden>
                <span>내역이 없습니다.</span>
            </div>
        </div>

        {{-- 모달 / 분류추가. --}}
        <div class="modal fade" id="code_div_add_edit" tabindex="-1" aria-hidden="true"
            style="display: none;--bs-modal-width:900px;">
            <div class="modal-dialog  modal-dialog-centered">
                <input type="hidden" class="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">분류 추가</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick="codeDetailModalClear();"></button>
                    </div>
                    <div class="modal-body">
                        {{--  공통코드명: select --}}
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">공통코드명</label>
                            <div class="col-sm-10">
                                <input class="form-control" id="code_inp_code_category" disabled>
                            </div>
                        </div>
                        {{-- max_step --}}
                        <div class="row justify-content-end gap-2 px-3" >
                            <span for="" class="col-sm-2 col-form-label border">MAX차 : <span id="code_sp_max_step"></span></span>

                            {{-- <button class="btn btn-outline-danger rounded" onclick="clodeStepDelete()">분류삭제</button>
                            <button class="btn btn-outline-primary rounded" onclick="codeStepAdd();">분류추가</button> --}}
                        </div>
                        {{-- n차분류  1줄 라인 --}}
                        <div id="code_div_modal_code" class="d-flex overflow-auto pb-2 align-middle mt-2">
                            <div class="step_cate d-flex border cursor-pointer" onclick="codeStepClick(this)">
                                <div class="d-flex align-items-center bg-light p-2" style="width:100px">
                                    <span class="step">1</span>
                                    차 분류
                                </div>
                                <div class="p-2">
                                    <input class="code_name border" placeholder="저장시 자동저장" onclick="event.stopPropagation();" onkeyup="if(event.keyCode == 13)this.closest('.step_cate').click();" disabled>
                                </div>
                                <input type="hidden" class="code_pt">
                            </div>
                        </div>
                        
                        <div id="code_div_modal_middle" hidden>
                            {{-- 항목추가btn --}}
                            <div class="d-flex justify-content-end gap-1">
                                <button class="btn btn-outline-danger rounded" onclick="codeTrSelDelete();">항목삭제</button>
                                <button class="btn btn-outline-primary rounded" onclick="codeTrAdd();">항목추가</button>
                            </div>
                            
                            <div class="mt-2">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:50px;" onclick="event.stopPropagation();this.querySelector('input').click();">
                                                <input type="checkbox" class="form-check-input" onclick="event.stopPropagation();codeAllChk(this);">
                                            </th>
                                            <th>항목명</th>
                                            <th>기능/매치</th>
                                            <th style="min-width:85px;">노출순위</th>
                                            <th>사용자 권한</th>
                                            <th>사용</th>
                                            <th style="min-width:120px;">기능</th>
                                        </tr>
                                    </thead>
                                    <tbody id="code_tby_modal_code">
                                        <tr class="copy_tr_modal_code" hidden>
                                            <td onclick="event.stopPropagation();this.querySelector('input').click();">
                                                <input type="checkbox" class="form-check-input chk" onclick="event.stopPropagation();">
                                            </td>
                                            <td data="항목명">
                                                <input type="text" class="code_name border w-100" onkeydown="codeKeyUpDetail(this);">
                                            </td>
                                            <td>
                                                <select class="function_code" disabled ></select>
                                            </td>
                                            <td data="노출순위">
                                                {{-- 위/아래 도형버튼 --}}
                                                <button class="btn btn-outline-primary rounded btn-sm p-0" onclick="codeStepUp(this);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill" viewBox="0 0 16 16">
                                                        <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
                                                      </svg>
                                                </button>
                                                <span class="code_idx">1</span>
                                                <button class="btn btn-outline-primary rounded btn-sm p-0" onclick="codeStepDown(this);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                                        <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                                                    </svg>
                                                </button>
                                            </td>
                                            <td data="사용자 권한">
                                                {{-- align-items: center --}}
                                                <div class="d-flex gap-2 align-items-center ">
                                                    <select class="form-select group_seq">
                                                        <option value="" selected>전체</option>
                                                    </select> 
                                                    /
                                                    <select class="form-select open_size" >
                                                        <option value="" selected>전체공개</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td data="사용">
                                                <input type="checkbox" class="form-check-input is_use">
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-outline-danger rounded btn-sm" onclick="codeTrDelete(this)">삭제</button>
                                                <button class="btn btn-outline-primary rounded btn-sm code_next_step" onclick="codeTrNextStep(this)" hidden>상세</button>
                                            </td>
                                            <input type="hidden" class="code_seq">
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div hidden>
                            <table class="table table-bordered">
                                <tr>
                                    <th class="table-light text-center align-middle">사용자 권한</th>
                                    <td class="col align-middle">
                                        {{-- select 초등 고가형 --}}
                                        <select class="form-select " aria-label=".form-select-sm example">
                                            <option selected>선택</option>
                                        </select>
                                    </td>
                                    <th class="table-light text-center align-middle">
                                        권한 외<br> 공개범위
                                    </th>
                                    <td class="col align-middle">
                                        {{-- div input label 비공개 / 페이지 검색 허용(내용 비공개) / 컨텐츠 열람 허용(전체공개) --}}
                                        <div>
                                            <input type="checkbox" class="form-check-input" id="code_chk_modal_btm1">
                                            <label class="form-check-label" for="code_chk_modal_btm1">비공개</label>
                                        </div>

                                        <div>
                                            <input type="checkbox" class="form-check-input" id="code_chk_modal_btm2" ">
                                            <label class="form-check-label" for="code_chk_modal_btm2">페이지 검색 허용(내용 비공개)</label>
                                        </div>

                                        <div>
                                            <input type="checkbox" class="form-check-input" id="code_chk_modal_btm3">
                                            <label class="form-check-label" for="code_chk_modal_btm3">컨텐츠 열람 허용(전체공개)</label>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="codeDetailModalClear();">취소</button>
                        <button class="btn btn-outline-primary rounded" onclick="codeInsert();">저장</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 강좌 분류 연결 기능. (학년, 과목, 시리즈, 시리즈 하위) --}}
        <div id="code_div_connect_add" class="position-absolute w-100 h-100 bg-white" style="top: 0; left: 0; z-index:3"
            hidden>
            @include('admin.admin_code_connect')
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            codeSelect('category');
        });
        function codeSelect(type, step, category, pt){
            let code_step = '';
            let code_category = '';
            let code_pt = '';
            let search_str = '';
            if(type == 'category'){
                code_step = 0;
                search_str = document.querySelector('#code_inp_search_str').value;
            }
            else if(type == 'codes'){
                code_step = step;
                code_category = category;
                code_pt = pt;
            }

            const page = '/manage/code/select';
            const parameter = {
                'code_step':code_step,
                'code_category':code_category,
                'code_pt':code_pt,
                'search_str':search_str,
            };
            queryFetch(page, parameter, function(result){
                console.log(result);
                if((result.resultCode||'') == 'success'){
                    if(type == 'category') codeSelectCategory(result.codes);
                    else if(type == 'codes') codeSelectCodes(result.codes, result.code_functions);
                }
            });

        }

        // 첫 화면 분류 리스트 가져오기.
        function codeSelectCategory(codes){
            //초기화
            const code_tby = document.querySelector('#code_tby');
            const copy_code_tr = code_tby.querySelector('.copy_tr_code').cloneNode(true);
            code_tby.innerHTML = '';
            code_tby.appendChild(copy_code_tr);
            copy_code_tr.hidden = true;

            //현재 열린 모달의 분류 카테고리를 가져온다.
            const code_category = document.querySelector('#code_inp_code_category').getAttribute('code_category');
            for(let i = 0; i < codes.length; i++){
                const code = codes[i];
                const tr = code_tby.querySelector('.copy_tr_code').cloneNode(true);
                tr.classList.remove('copy_tr_code');
                tr.classList.add('tr_code');
                tr.hidden = false;

                //로딩 제거.
                const loding_place = tr.querySelectorAll('.loding_place');
                for(let i = 0; i < loding_place.length; i++){
                    loding_place[i].remove();
                }
                tr.querySelector('.btn_function').hidden = false;
                tr.querySelector('.code_name').innerText = code.code_name;
                tr.querySelector('.max_step').innerText = code.max_step ?? 0;
                tr.querySelector('.code_names').innerText = code.code_names;
                tr.querySelector('.code_seq').value = code.id;
                tr.querySelector('.code_category').value = code.code_category;

                //현재 열린 모달(상세)의 분류 카테고리와 같으면 max_step을 수정.
                if(code_category == code.code_category){
                    const sp_code_step = document.querySelector('#code_sp_max_step');
                    sp_code_step.innerText = code.max_step;
                }

                code_tby.appendChild(tr);
            }
        }

        // 분류리스트 상세 버튼 클릭 항목 가져오기.
        function codeSelectCodes(codes, code_functions){
            //초기화
            const tby_modal_code = document.querySelector('#code_tby_modal_code');
            const copy_tr_modal_code = code_tby_modal_code.querySelector('.copy_tr_modal_code').cloneNode(true);
            tby_modal_code.innerHTML = '';
            tby_modal_code.appendChild(copy_tr_modal_code);
            copy_tr_modal_code.hidden = true;

            //vdom 만든후 code_functions를 넣어준다.
            const vdom = document.createDocumentFragment();
            for(let i = 0; i < code_functions.length; i++){
                const code_function = code_functions[i];
                const option = document.createElement('option');
                option.value = code_function.function_code;
                option.innerText = code_function.function_name;
                vdom.appendChild(option);
            }

            const vdom_option_cnt = vdom.querySelectorAll('option').length;
            // 1~n차 분류의 항목 가져오기.
            for(let i = 0; i < codes.length; i++){
                const code = codes[i];
                const tr = tby_modal_code.querySelector('.copy_tr_modal_code').cloneNode(true);
                tr.classList.remove('copy_tr_modal_code');
                tr.classList.add('tr_modal_code');
                tr.hidden = false;

                tr.querySelector('.chk').hidden = false;
                tr.querySelector('.code_name').value = code.code_name;
                tr.querySelector('.code_idx').innerText = code.code_idx;
                tr.querySelector('.group_seq').value = code.group_seq || '';
                tr.querySelector('.open_size').value = code.open_size || '';
                tr.querySelector('.is_use').checked = code.is_use == 'Y' ? true : false;
                tr.querySelector('.code_seq').value = code.id;
                tr.querySelector('.code_next_step').hidden = false;
                if((code.code_names||'') != ''){
                    tr.querySelector('.code_next_step').innerText = '수정';
                    tr.querySelector('.code_next_step').classList.add('btn-outline-success');
                }
                if(vdom_option_cnt > 0){
                    tr.querySelector('.function_code').appendChild(vdom.cloneNode(true));
                    tr.querySelector('.function_code').disabled = false;
                    if((code.function_code || '') != '')
                        tr.querySelector('.function_code').value = code.function_code;
                }


                tby_modal_code.appendChild(tr);
            }
        }

         // 분류추가 모달 > 분류 클릭
        function codeStepClick(vthis){
            event.stopPropagation();
            const code_div_modal_code = document.querySelector('#code_div_modal_code');
            const step_cate = code_div_modal_code.querySelectorAll('.step_cate');
            const code_div_modal_middle = document.querySelector('#code_div_modal_middle');

                step_cate.forEach(el => {
                    el.classList.remove('border-primary');
                    el.classList.remove('active');
                    // vthis 보다 뒤에 있는 step_cate는 삭제한다.
                    if(el.querySelector('.step').innerText > vthis.querySelector('.step').innerText){
                        el.remove();
                    }
                });
                vthis.classList.add('active');
                vthis.classList.add('border-primary');
                code_div_modal_middle.hidden = false;

                
                
                const category = document.querySelector('#code_inp_code_category').getAttribute('code_category');
                const step = vthis.querySelector('.step').innerText;
                const code_pt = vthis.querySelector('.code_pt').value;
                //선택 항목 가져오기.
                codeSelect('codes', step, category, code_pt);
        }

        // 분류 추가 모달 > n차 +1 증가 후 태그 추가
        function codeStepAdd(code_pt, code_name) {  
            const code_div_modal_code = document.querySelector('#code_div_modal_code');
            const step_cate = code_div_modal_code.querySelector('.step_cate').cloneNode(true);
            const step_cate_last = document.querySelector('.step_cate:last-child');
            const step_cate_last_step = step_cate_last.querySelector('.step').innerText;
            const step_cate_last_step_plus = Number(step_cate_last_step) + 1;

            step_cate.querySelector('.step').innerText = step_cate_last_step_plus;
            step_cate.querySelector('.code_name').value = code_name;
            step_cate.querySelector('.code_pt').value = code_pt;
            code_div_modal_code.appendChild(step_cate);
            step_cate.click();
        }

        // 분류 추가 모달 > 분류 삭제
        function clodeStepDelete(){
            const code_div_modal_code = document.getElementById('code_div_modal_code');
            //마지막 분류 삭제
            const step_cate_last = code_div_modal_code.querySelector('.step_cate:last-child');
            const step = step_cate_last.querySelector('.step').innerText;
            const id = step_cate_last.querySelector('.id').value;

            if(step == 1){
                toast('최소 1차 분류는 삭제할 수 없습니다.');
                return false;
            }
            //만약 ID가 있으면 삭제 진행
            if(id.length > 0){

            }else{
                step_cate_last.remove();
            }            
        }

        // 분류 추가 모달 > 항목추가
        function codeTrAdd(){
            const code_tby_modal_code = document.querySelector('#code_tby_modal_code');
            const copy_tr = code_tby_modal_code.querySelector('.copy_tr_modal_code').cloneNode(true);

            copy_tr.classList.remove('copy_tr_modal_code');
            copy_tr.classList.add('tr_modal_code');
            copy_tr.hidden = false;
            copy_tr.querySelector('.code_name').value = '';
            copy_tr.querySelector('.code_seq').value = '';
            code_tby_modal_code.appendChild(copy_tr);
            //노출순위 마지막 값 + 1
            const code_idx = copy_tr.querySelector('.code_idx');
            const code_idx_text = code_tby_modal_code.querySelectorAll('.tr_modal_code').length;
            code_idx.innerText = code_idx_text;

        }

        // 분류 추가 모달 > 항목 삭제
        function codeTrDelete(vthis){
            const tr = vthis.closest('tr');
            const code_seq = tr.querySelector('.code_seq').value;
            sAlert('삭제', '항목을 삭제하시겠습니까? 하위 항목까지 모두 삭제됩니다.',2, function(){
                if(code_seq.length > 0){
                    //DB 삭제
                    codeDelete(code_seq);
                }else{
                    tr.remove();
                }
            });
        }

        // 분류 추가 모달 > 항목 > 노출순위 업
        function codeStepUp(vthis){
            const tr = vthis.closest('tr');
            const code_step = tr.querySelector('.code_idx');
            const code_stop_text = Number(code_step.innerText) + 1;
            code_step.innerText = code_stop_text;
        }

        // 분류 추가 모달 > 항목 > 노출순위 다운
        function codeStepDown(vthis){
            const tr = vthis.closest('tr');
            const code_step = tr.querySelector('.code_idx');
            if(code_step.innerText == 1){
                //1은 최소값
                toast('최소값입니다.');
                return false;
            }
            const code_stop_text = Number(code_step.innerText) - 1;
            code_step.innerText = code_stop_text;
        }

        // 분류 추가 모달 선택 강좌의 정보 가져오기
        function codeDetailSettingModal(vthis){
            codeDetailModalClear();
            const tr = vthis.closest('tr');
            const modal = document.querySelector('#code_div_add_edit');
            const code_name = tr.querySelector('.code_name').innerText;
            const code_seq = tr.querySelector('.code_seq').value;
            const code_category = tr.querySelector('.code_category').value;
            const max_step = tr.querySelector('.max_step').innerText;

            const inp_code_category = modal.querySelector('#code_inp_code_category');
            inp_code_category.value = code_name;
            inp_code_category.setAttribute('code_seq', code_seq);
            inp_code_category.setAttribute('code_category', code_category);
            const sp_code_step = modal.querySelector('#code_sp_max_step');
            sp_code_step.innerText = max_step;

            //1~n차 분류 가져오기.

            //1차분류 이름 자동 입력
            const div_modal_code = document.querySelector('#code_div_modal_code');
            div_modal_code.querySelector('.code_name').value = code_name;
            div_modal_code.querySelector('.code_pt').value = code_seq;

            //1차 분류 클릭
            const step_cate = div_modal_code.querySelector('.step_cate');
            step_cate.click();
        }

        // 분류 추가 모달 > 초기화
        function codeDetailModalClear(){
            const modal = document.querySelector('#code_div_add_edit');
            modal.querySelector('#code_div_modal_code').value = '';
            modal.querySelector('#code_sp_max_step').value = '';
            // 1~n 초기화
            const div_modal_code = document.querySelector('#code_div_modal_code');
            const step_cate = code_div_modal_code.querySelector('.step_cate').cloneNode(true);
            const cate_name = step_cate.querySelector('.code_name');
            const code_pt = step_cate.querySelector('.code_pt');
            step_cate.classList.remove('border-primary');
            step_cate.classList.remove('active');
            div_modal_code.innerHTML = '';
            div_modal_code.appendChild(step_cate);
            cate_name.value = '';
            code_pt.value = '';

            //항목 초기화
            const code_tby_modal_code = document.querySelector('#code_tby_modal_code');
            const copy_tr_modal_code = code_tby_modal_code.querySelector('.copy_tr_modal_code').cloneNode(true);
            code_tby_modal_code.innerHTML = '';
            code_tby_modal_code.appendChild(copy_tr_modal_code);
            copy_tr_modal_code.hidden = true; 
        }

        // 공통코드(분류) 저장
        function codeInsert(){
            const modal = document.querySelector('#code_div_add_edit');
            //선택 분류 카테고리를 가져온다.
            const code_category = modal.querySelector('#code_inp_code_category').getAttribute('code_category');

            //현재 스탭 step_cate active 가져온다.
            const div_modal_code = document.querySelector('#code_div_modal_code');
            const step_cate = div_modal_code.querySelector('.step_cate.active');
            const step = step_cate.querySelector('.step').innerText;
            const code_pt = step_cate.querySelector('.code_pt').value;

            // 항목들을 가져온다.
            const trs = document.querySelectorAll('#code_tby_modal_code .tr_modal_code');
            const code_data = [];
            for(let i = 0; i < trs.length; i++){
                const tr = trs[i];
                const code_name = tr.querySelector('.code_name').value;
                const code_idx = tr.querySelector('.code_idx').innerText;
                const group_seq = tr.querySelector('.group_seq').value;
                const open_size = tr.querySelector('.open_size').value;
                const is_use = tr.querySelector('.is_use').checked ? 'Y' : 'N';
                const code_seq = tr.querySelector('.code_seq').value;
                const function_code = tr.querySelector('.function_code').value;

                const data = {
                    'code_name':code_name,
                    'code_idx':code_idx,
                    'group_seq':group_seq,
                    'open_size':open_size,
                    'is_use':is_use,
                    'code_seq':code_seq,
                    'code_pt':code_pt,
                    'function_code':function_code,
                };
                if(data.code_seq.length > 0 || data.code_name.length > 0)
                    code_data.push(data);
            }

            const page = '/manage/code/insert';
            const parameter = {
                'code_category':code_category,
                'step':step,
                'code_data':code_data,
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('저장되었습니다.');
                    // codeDetailModalClear();
                    codeSelect('category');
                    codeSelect('codes', step, code_category, code_pt);
                }
            });
        }

        // 분류 추가 모달 > 항목 이름 엔터
        function codeKeyUpDetail(vthis){
            // 엔터시 한칸 더하고
            if(event.keyCode == 13){
                event.preventDefault();
                codeTrAdd();
                //포커스를 다음 항목으로 이동
                const trs = document.querySelectorAll('#code_tby_modal_code .tr_modal_code');
                const tr = vthis.closest('tr');
                const idx = Array.prototype.indexOf.call(trs, tr);
                const next_tr = trs[idx+1];
                if(next_tr){
                    next_tr.querySelector('.code_name').focus();
                }
            }
        }

        // 분류 추가 모달 > 항목 > 삭제
        function codeDelete(code_seqs){
            if(code_seqs.length == 0){
                return false;
            }
            const page = '/manage/code/delete';
            const parameter = {
                'code_seqs':code_seqs,
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('삭제되었습니다.');

                    const step = document.querySelector('.step_cate.active .step').innerText;
                    const code_category = document.querySelector('#code_inp_code_category').getAttribute('code_category');
                    const code_pt = document.querySelector('.step_cate.active .code_pt').value;
                    codeSelect('codes', step, code_category, code_pt);
                    codeSelect('category');
                }
            });
        }

        // 분류 추가 모달 > 항목 > 상세 클릭
        function codeTrNextStep(vthis){
            const tr = vthis.closest('tr');
            const code_seq = tr.querySelector('.code_seq').value;
            const code_name = tr.querySelector('.code_name').value;

            codeStepAdd(code_seq, code_name);
        }

        // 분류 추가 모달 > 항목 > 선택 삭제.
        function codeTrSelDelete(){
            const trs = document.querySelectorAll('#code_tby_modal_code .tr_modal_code');
            let code_seqs = "";
            for(let i = 0; i < trs.length; i++){
                const tr = trs[i];
                const chk = tr.querySelector('.chk');
                if(chk.checked){
                    const code_seq = tr.querySelector('.code_seq').value;
                    if(code_seq.length > 0){
                        if(code_seqs != "")
                            code_seqs += ',';
                        code_seqs += code_seq;
                    }
                }
            }
            if(code_seqs.length == 0){
                toast('선택된 항목이 없습니다.');
                return false;
            }
            sAlert('삭제', '선택된 항목을 삭제하시겠습니까? 하위 항목까지 모두 삭제됩니다.',2, function(){
                codeDelete(code_seqs);
            });
        }

        // 전체 체크, 해제
        function codeAllChk(vthis){
            const chk = vthis.checked;
            const trs = document.querySelectorAll('#code_tby_modal_code .tr_modal_code');
            for(let i = 0; i < trs.length; i++){
                const tr = trs[i];
                const chk = tr.querySelector('.chk');
                chk.checked = vthis.checked;
            }
        }
    </script>
@endsection
