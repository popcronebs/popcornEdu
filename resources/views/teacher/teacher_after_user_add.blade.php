@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    사용자 등록(개별)
@endsection

@section('add_css_js')
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
@endsection

{{-- 컨텐트 --}}
@section('layout_coutent')
        <div class="row pt-2 zoom_sm" data-div-main="after_user_add">
            <input type="hidden" data-main-teach-seq value="{{ $teach_seq }}">
            <input type="hidden" data-main-team-code  value="{{ $team->team_code }}">
            <input type="hidden" data-student-main-group value="{{ $student_group->id }}">
            <input type="hidden" data-parent-main-group value="{{ $parent_group->id }}">
            <input type="hidden" data-edit-student-seq>


            <div class="sub-title d-flex justify-content-between">
                <h2 class="text-sb-42px">
                    <button data-btn-back-page="" class="btn p-0 row mx-0 all-center" onclick="teachAfUAddBack();">
                        <img src="https://sdang.acaunion.com/images/black_arrow_left_tail.svg" width="52" class="px-0">
                    </button>
                    <span class="me-2">사용자 등록(개별)</span>
                </h2>
            </div>

            <div style="border-top: solid 2px #222;" class="w-100"></div>
            <table data-tb="0" class="w-100 table-list-style table-border-xless table-h-92" style="border-top:0px;">
                <colgroup>
                    <col style="width: 15%;">
                    <col style="width: 35%;">
                    <col style="width: 15%;">
                    <col style="width: 35%;">
                </colgroup>
                <thead>
                  <tr data-tr-first hidden>
                    <td class="text-start ps-4 scale-text-gray_06">등록 정보</td>
                    <td data-tb-index class=" p-3 text-end" colspan="3">
                      <div class="h-center justify-content-between">
                        <div class="col text-sb-20px">
                            <span class="text-dark" data-user-index></span> 번째 사용자 추가
                        </div>
                        <div class="col-auto">
                            <button data-btn-toggle-table class="btn p-0 m-0 h-center" onclick="teachUserAddOpenTbody(this);">
                                <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" width="32" height="32">
                            </button>
                        </div>
                      </div>
                    </td>
                  </tr>
                </thead>
                <tbody>
                    <tr class="text-start scale-bg-gray_01">
                        <td class="text-start ps-4 scale-text-gray_06"> 구분 </td>
                        <td class="text-start p-3">
                            <div data-user-name data-text=""
                                class="text-m-24px px-0 scale-text-gray_05 w-100 rounded-3 is_content">
                                {{-- 그룹인데 일단은 방과후 학생으로 고정. --}}
                                방과후 학생
                            </div>
                        </td>
                        <td class="text-start ps-4 scale-text-gray_06">학교</td>
                        <td class="text-start px-4">
                        {{-- 방과후는 팀이름이 학교이름이므로 학교이름 변수명. --}}
                        <div data-school-name
                          class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                                {{ $team->team_name }}
                            </div>
                        </td>
                    </tr>
                    <span ></span>
                    <tr class="text-start">
                        <td class="text-start ps-4 scale-text-gray_06">이름<span class="text-danger">(필수)</span></td>
                        <td class="text-start p-3">
                            <div  data-text=""
                                class="text-m-24px px-0 scale-text-gray_05 w-100 rounded-3 is_content">
                                <input data-student-name class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3">
                            </div>
                        </td>
                        <td class="text-start ps-4 scale-text-gray_06 scale-bg-gray_01">휴대전화</td>
                        <td class="text-start px-4 scale-bg-gray_01">
                            <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                                <input data-student-phone class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" hidden>
                                <span class="text-m-20px">가입시 입력</span>
                            </div>
                        </td>
                    </tr>
                    <tr class="text-start">
                        <td class="text-start ps-4 scale-text-gray_06 scale-bg-gray_01">학부모성함</td>
                        <td class="text-start p-3 scale-bg-gray_01">
                            <div data-user-name data-text=""
                                class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                                <input data-parent-name class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" hidden>
                                <span class="text-m-20px">가입시 입력</span>
                            </div>
                        </td>
                        <td class="text-start ps-4 scale-text-gray_06">학부모 휴대전화<span class="text-danger">(필수)</span></td>
                        <td class="text-start px-4 py-3">
                            <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                                <input data-parent-phone class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start ps-4 scale-text-gray_06">학년</td>
                        <td class="text-start p-3">
                            <div data-text=""
                                class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                                {{-- //select box --}}
                            <div class="d-inline-block select-wrap select-icon w-100 ">
                                <select data-grade-name
                                class="search_type border-gray lg-select text-sb-20px w-100 h-62 border-none">
                                    <option value="">학년 선택</option>
                                    @if (!empty($grade_codes))
                                    @foreach ($grade_codes as $grade_code)
                                    <option value="{{ $grade_code->id }}">{{ $grade_code->code_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                </div>

                            </div>
                        </td>
                        <td class="text-start ps-4 scale-text-gray_06">반</td>
                        <td class="text-start py-3 px-4">
                            <div data-student-class-name
                            class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                              <input data-student-st-class-name class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="반을 입력해주세요.">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start ps-4 scale-text-gray_06 scale-bg-gray_01">주소</td>
                        <td class="text-start py-3 px-4 scale-bg-gray_01" colspan="3">
                        <div class="h-center justify-content-between gap-2">
                            <div data-text=""
                            class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                                <input data-student-address class="text-m-20px px-2 scale-text-gray_05 border p-2 w-100 rounded-3" hidden>
                            </div>
                            <div class="col-auto" hidden>
                                <button type="button" onclick=" teachAfUAddDaumPostcode()"
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">주소 검색</button>
                            </div>
                        </div>
                        <div id="first_login_div_address_wrap" style="border:1px solid;width:100%;height:300px;margin:5px 0;position:relative" hidden>
                            <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="teachAfUAddFoldDaumPostcode()" alt="접기 버튼">
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start ps-4 scale-text-gray_06">방과후 클래스</td>
                        <td class="text-start p-3">
                            <div class="d-inline-block select-wrap select-icon w-100 ">
                                <select data-class-seq
                                class="search_type border-gray lg-select text-sb-20px w-100 h-62 border-none">
                                    <option value="">방과후 클래스 선택</option>
                                    @if (!empty($classes))
                                        @foreach ($classes as $class)
                                          <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </td>
                        <td class="text-start ps-4 scale-text-gray_06">이용기간</td>
                        <td class="text-start px-4">
                           <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                                <div class="col-auto h-center border-none rounded-pill">
                                    <img src="{{ asset('images/calendar_gray_icon.svg') }}" class="me-2">
                                    {{-- :날짜시간 PICKER 2 --}}
                                    <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start" style="height: 25px;">
                                        <div class="h-center justify-content-between">
                                            <div data-date onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                                                type="text" class="text-m-20px text-start scale-text-gray_05" readonly="" placeholder="">
                                                {{-- 상담시작일시 --}}
                                                {{ date('y.m.d') }}
                                            </div>
                                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                                        </div>
                                        <input type="date" style="width: 1px;height: 0.5px;" data-class-start-date
                                            oninput="userPaymentDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                                    </div>
                                    ~
                                    <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start" style="height: 25px;">
                                        <div class="h-center justify-content-between">
                                            <div data-date onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                                                type="text" class="text-m-20px text-start scale-text-gray_05" readonly="" placeholder="">
                                                {{-- 상담시작일시 --}}
                                                {{ date('y.m.d') }}
                                            </div>
                                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                                        </div>
                                        <input type="date" style="width: 1px;height: 0.5px;" data-class-end-date
                                            oninput="userPaymentDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                                    </div>

                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr data-foot-tr>
                      <td colspan="4" class="">
                        <div class="text-end px-4">
                            <button type="button" onclick="teachAfUAddTempIdPwButton()"
                            class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white text-dark px-3 me-2 align-bottom">임시아이디 및 비밀번호 발급</button>
                        </div>
                      </td>
                    </tr>
                    <tr class="scale-text-gray_05">
                        <td class="text-start ps-4 scale-text-gray_06">임시 아이디</td>
                        <td class="text-start p-3">
                            <div data-student-tmp-id data-text=""
                                class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">

                            </div>
                        </td>
                        <td class="text-start ps-4 scale-text-gray_06">임시 비밀번호</td>
                        <td class="text-start px-4">
                            <div data-student-tmp-pw
                            class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                            </div>
                        </td>
                    </tr>
                    <tr data-foot-aside>
                        <td colspan="4" class="scale-bg-gray_01">
                        <div class="h-center justify-content-between px-4">
                           <div class="col text-start">
                            <button type="button" onclick="teachAfUAddPrint();"
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 ">
                                <img src="{{ asset('images/print_icon.svg') }}" width="24">
                                인쇄하기
                            </button>
                           </div>
                           <div class="col text-end">
                                <button type="button" onclick="teachAfUAddDelete(this)" data-btn-delete hidden
                                class="btn-line-xss-secondary text-sb-20px border rounded scale-text-gray_05 scale-bg-white px-3 me-2 align-bottom">삭제하기</button>
                                <button type="button" onclick="teachAfUAddInsert(this)" data-btn-save
                                class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white text-dark px-3 me-2 align-bottom">저장하기</button>
                                <button type="button" onclick="teachAfUAddCancel(this)" data-btn-cancel hidden
                                class="btn-line-xss-secondary text-sb-20px border rounded scale-text-gray_05 scale-bg-white px-3 me-2 align-bottom">취소하기</button>
                           </div>
                        </div>
                        </td>
                    </tr>

                </tbody>
            </table>


        <div data-explain="32px">
            <div class="py-lg-3"></div>
        </div>

        <div data-explain="개인정보 수집 및 동의여부" style="display:none !important;"
        class="scale-bg-gray_01 rounded px-52 py-32 d-flex flex-column row-gap-3 text-sb-24px mt-52 mb-52" >
          <label class="checkbox d-flex align-items-center">
            <input type="checkbox" class="" disabled>
            <span class=""></span>
            <p class="ms-2">개인정보 수집 및 이용 동의여부</p>
            <p class="ms-1 scale-text-gray_05">(동의일자 : 2023.01.01)</p>
          </label>
          <label class="checkbox d-flex align-items-center">
            <input type="checkbox" class="" disabled>
            <span class=""></span>
            <p class="ms-2">이용약관 동의여부</p>
            <p class="ms-1 scale-text-gray_05">(동의일자 : 2023.01.01)</p>
          </label>
          <label class="checkbox d-flex align-items-center">
            <input type="checkbox" class="" disabled>
            <span class=""></span>
            <p class="ms-2">제3자 정보제공 동의여부</p>
            <p class="ms-1 scale-text-gray_05">(동의일자 : 2023.01.01)</p>
          </label>
          <label class="checkbox d-flex align-items-center">
            <input type="checkbox" class="" disabled>
            <span class=""></span>
            <p class="ms-2">마케팅 등 수집이용 동의여부</p>
            <p class="ms-1 scale-text-gray_05">(동의일자 : 2023.01.01)</p>
          </label>
        </div>

        <div class="w-center gap-4" hidden>
            <button type="button" onclick=" teachAfUAddInputAdd();" style="width:170px"
            class="btn-line-ms-secondary text-sb-20px rounded-pill border-gray scale-bg-white scale-text-gray_05 border w-center">추가 등록하기</button>
            <button type="button" onclick="teachAfUAddInsert();" style="width:170px"
            class="btn-line-ms-secondary text-sb-20px rounded-pill bg-primary-y text-white border w-center">일괄 등록하기</button>
        </div>

        <p class="text-b-28px mt-80 mb-4">오늘 등록 내역</p>
        <div style="border-top: solid 2px #222;" class="w-100"></div>
        <div data-div-add-table-bundle>

        </div>

        <table data-tb="0" class="w-100 table-list-style table-border-xless table-h-92" style="border-top:0px;">
            <colgroup>
                <col style="width: 15%;">
                <col style="width: 35%;">
                <col style="width: 15%;">
                <col style="width: 35%;">
            </colgroup>
            <tbody data-bundle="today_insert_students">
                <tr data-row="copy"
                onclick="teachAfUAddInfoSelect(this);" hidden>
                    <input type="hidden" data-student-seq>
                    <td>
                        <span data-tb-index></span>
                    </td>
                    <td colspan="3" class="text-start px-4">
                        <span data-student-name></span>
                    </td>
                </tr>
            </tbody>
        </table>


        <div class="" data-explain="160px">
            <div class="py-lg-5"> </div>
            <div class="py-lg-4"> </div>
            <div class="py-lg-3"> </div>
        </div>
    </div>

{{-- action = /teacher/after/users/add/list --}}
    <form actoin="/teacher/after/users/add/list" method="post" id="form" hidden>
        <input type="hidden" name="student_seq" value="">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
           teachAfUAddTodayList();
        });
    // 뒤로가기
       function teachAfUAddBack(){
            location.href = '/teacher/student/after';
            // sessionStorage.setItem('isBackNavigation', 'true');
            // window.history.back();
       }

       //날짜 선택
        function userPaymentDateTimeSel(vthis){
            const date = new Date(vthis.value);
            vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = date.format('yy.MM.dd')
        }

        // 주소 검색
                //우편번호찾기. 숨기기 / 가져오기
        const element_wrap = document.getElementById('first_login_div_address_wrap');
        function teachAfUAddFoldDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
            element_wrap.hidden = true;
        }

        function teachAfUAddDaumPostcode() {
        // 현재 scroll 위치를 저장해놓는다.
            var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
            new daum.Postcode({
                oncomplete: function(data) {
                    var addr = ''; // 주소 변수
                    var extraAddr = ''; // 참고항목 변수
                    //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                    if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                        addr = data.roadAddress;
                    } else { // 사용자가 지번 주소를 선택했을 경우(J)
                        addr = data.jibunAddress;
                    }

                    if(data.userSelectedType === 'R'){
                        // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                        // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                        if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                            extraAddr += data.bname;
                        }
                        // 건물명이 있고, 공동주택일 경우 추가한다.
                        if(data.buildingName !== '' && data.apartment === 'Y'){
                            extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                        }
                        // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                        if(extraAddr !== ''){
                            extraAddr = ' (' + extraAddr + ')';
                        }
                        // 조합된 참고항목을 해당 필드에 넣는다.
                        document.querySelector("[data-student-address]").value = extraAddr;
                    } else {
                        document.querySelector("[data-student-address]").value = '';
                    }

                    // 우편번호와 주소 정보를 해당 필드에 넣는다.
                    const zip_code = data.zonecode;
                    const address = addr;
                    document.querySelector("[data-student-address]").value = `[우편번호:${zip_code}] ${addr}`;

                    // iframe을 넣은 element를 안보이게 한다.
                    // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                    element_wrap.hidden = true;

                    // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                    document.body.scrollTop = currentScroll;
                },
                // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
                onresize : function(size) {
                    let sheight = size.height;
                    if(size.height > 400)
                        sheight = 400;
                    element_wrap.style.height = sheight+'px';
                },
                width : '100%',
                height : '100%'
            }).embed(element_wrap);

            // iframe을 넣은 element를 보이게 한다.
            element_wrap.hidden = false;
        }

        // 임시아이디 및 비밀번호 발급
        function teachAfUAddTempIdPwButton(){
            const msg =
            `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
            <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">임시아이디/비밀번호를 발급하시겠습니까?</p>
            </div>
            `;
            sAlert('', msg, 3, function() {
                document.querySelector('[data-student-tmp-id]').innerHTML = teachAfUAddTempId();
                document.querySelector('[data-student-tmp-pw]').innerHTML = '1234';
            }, null, '발급하기', '취소');
        }

        var main_idx = 0;
        function teachAfUAddTempId(type){
          let dTime = '';

          main_idx++;
          dTime = new Date();
          let req_time = dTime.getTime();
          req_time = (req_time.toString()).substr(-9);
          //랜덤값 무조건 4자리
          req_time += Math.floor(Math.random() * 10000).toString().padStart(4, "0");
            let type_str = 'st';
          if(type == 'parent') type_str = 'pt';
          let tempID = 'temP' + type_str+req_time.toString() + main_idx.toString().padStart(4, "0");

          return tempID;
        }

        // 추가 등록하기.
        function teachAfUAddInputAdd() {
            const tb = document.querySelector('[data-tb="0"]').cloneNode(true);
            //data-tb 값 증가
            const tb_idx = document.querySelectorAll('[data-tb').length;
            tb.setAttribute('data-tb', tb_idx);

            const user_index_el = tb.querySelector('[data-user-index]');
            user_index_el.innerText = (tb_idx*1) + 1;

            // data-btn-toggle-table class add rotate-180
            const btn_toggle_table = tb.querySelector('[data-btn-toggle-table]');
            btn_toggle_table.classList.add('rotate-180');

            // tbody 숨기기
            const tbody = tb.querySelector('tbody');
            tbody.hidden = true;
            const bundle = document.querySelector('[data-div-add-table-bundle]');

            // input = '' select = '' 으로 변경
            const inputs = tb.querySelectorAll('input');
            const selects = tb.querySelectorAll('select');
            inputs.forEach(function(input){
                input.value = '';
            });
            selects.forEach(function(select){
                select.value = '';
            });

            // 구분 tr 숨김처리. / 주소 tr 숨김처리.
            tb.querySelector('[data-school-name]').closest('tr').hidden = true;
            tb.querySelector('[data-student-address]').closest('tr').hidden = true;
            tb.querySelector('[data-foot-tr]').hidden = true;
            tb.querySelector('[data-tr-first]').hidden = false;

            tb.querySelector('[data-student-tmp-id]').innerHTML = teachAfUAddTempId();
            tb.querySelector('[data-student-tmp-pw]').innerHTML = '1234';
            tb.querySelector('[data-foot-aside]').hidden = false;
           bundle.appendChild(tb);
        }

        // 사용자 저장.
        function teachAfUAddInsert(vthis){
            //vthis가 있으면 개별 저장, 없으면 일괄 저장.
            const arr_user = [];
            let max_len = document.querySelectorAll('[data-tb]').length;
            let temp_id_str = '';
            let temp_pw_str = '';
            let tmep_id_idx = 1;
            let tbs = document.querySelectorAll('[data-tb]');
            if(vthis){
                max_len = 1;
                tbs = [vthis.closest('[data-tb]')];
            }else{
                max_len = document.querySelectorAll('[data-tb]').length;
            }
            for(let i = 0; i < max_len; i++){
                const tb = tbs[i];
                let user_id = tb.querySelector('[data-student-tmp-id]').innerText;
                const group_seq = document.querySelector('[data-student-main-group]').value;
                const pt_group_seq = document.querySelector('[data-parent-main-group]').value;
                const area = '';
                const region_seq = '';
                const team_code = document.querySelector('[data-main-team-code]').value;
                const user_name = tb.querySelector('[data-student-name]').value;
                const user_phone = tb.querySelector('[data-student-phone]').value;
                const grade_seq = tb.querySelector('[data-grade-name]').value;
                const st_class_name = tb.querySelector('[data-student-st-class-name]').value;
                const class_seq = tb.querySelector('[data-class-seq]').value;
                const address = tb.querySelector('[data-student-address]').value;
                const class_start_date = tb.querySelector('[data-class-start-date]').value;
                const class_end_date = tb.querySelector('[data-class-end-date]').value;
                const school_name = tb.querySelector('[data-school-name]').innerText;

                let user_pw = tb.querySelector('[data-student-tmp-pw]').innerText;
                const is_auth = 'N' ;
                let user_type = 'student;'

                const parent_group = document.querySelector('[data-parent-main-group]').value;
                const parent_name = tb.querySelector('[data-parent-name]').value;
                const parent_phone = tb.querySelector('[data-parent-phone]').value;


                if(user_id == ''){
                    temp_id_str = '아이디가 비워져 있으면 임시 아이디를 생성합니다.';
                    user_id = teachAfUAddTempId();
                }
                if(user_pw == ''){
                    temp_pw_str = '비밀번호가 비워져 있으면 임시 비밀번호는 1234입니다.';
                    user_pw = '1234';
                    user_pw_chk = '1234';
                }
                let cnt_str = (i+1)+'번 ';
                if(user_name == ''){
                    if(tbs.length == 1) cnt_str = '';
                    toast(cnt_str+'사용자 등록의 이름을 입력해주세요.');
                    return;
                }
                // 학부모 이름
                /* if(parent_name == ''){ */
                /*     if(tbs.length == 1) cnt_str = ''; */
                /*     toast(cnt_str+'사용자 등록의 학부모 이름을 입력해주세요.'); */
                /*     return; */
                /* } */
                // 학부모 휴대전화
                if(parent_phone == ''){
                    if(tbs.length == 1) cnt_str = '';
                    toast(cnt_str+'사용자 등록의 학부모 휴대전화를 입력해주세요.');
                    return;
                }

                const parent_id = teachAfUAddTempId('parent');
                                //학부모 정보 입력.
                arr_user.push({
                    user_id:parent_id,
                    group_seq:pt_group_seq,
                    area:area,
                    team_code: team_code,
                    user_name: '가입시입력',
                    user_phone: parent_phone,
                    user_pw:'1234',
                    user_type:'parent',
                    grade_seq:grade_seq,
                    address:address,
                });
                arr_user.push({
                    user_id: user_id,
                    group_seq: group_seq,
                    area: area,
                    region_seq: region_seq,
                    team_code: team_code,
                    user_name: user_name,
                    user_phone: user_phone,
                    user_pw: user_pw,
                    is_auth: is_auth,
                    user_type:'student',
                    grade_seq: grade_seq,
                    st_class_name: st_class_name,
                    class_seq: class_seq,
                    address: address,
                    class_start_date: class_start_date,
                    class_end_date: class_end_date,
                    school_name: school_name,
                    parent_phone: parent_phone,
                    parent_id: parent_id,
                });
            }
            /* arrInsert */
            const page = "/teacher/users/add/excel/insert";
            const parameter = {
                users: arr_user
            };
            const msg =
            `
            <div class="text-m-28px">사용자 등록을 진행하시겠습니까?</div>
            `;
            sAlert('', msg, 3, function(){
                queryFetch(page, parameter, function(result) {
                    if ((result.resultCode || '') == 'success') {
                        const cnt_ap_student = result.cnt_already_phone_student;
                        const cnt_ap_parent = result.cnt_already_phone_parent;
                        const cnt_ap_teacher = result.cnt_already_phone_teacher;
                        const arr_phone = result.arr_phone;

                        const msg_in =
                        `<div class="text-m-28px">사용자 등록이 완료되었습니다.</div>
                        <div class="text-m-18px text-danger mt-3">${temp_id_str}</div>
                        <div class="text-m-18px text-danger">${temp_pw_str}</div>
                        `;

                        setTimeout(function(){
                            sAlert('', msg_in, 4, function(){
                                //개별 저장일경우 개별 삭제 처리.
                                if(vthis){
                                    // tbs[0].remove();
                                    // teachAfUAddTodayList();
                                    teachAfUAddClear();
                                    teachAfUAddBack();
                                }else{
                                    // 기획 요청에 의해 일괄 추가는 숨김처리.
                                    teachAfUAddClear();
                                    teachAfUAddBack();
                                }
                            });
                        },1000);
                    }else{
                        toast('다시 시도 해주시기 바랍니다.');
                    }
                });
            });
        }

        // 초기화 기능.
        function teachAfUAddClear(){
          //새로고침
          location.reload();
        }

        // 오픈/클로즈 /토글 테이블
        function teachUserAddOpenTbody(vthis){
            const tb = vthis.closest('[data-tb]');
            const tbody = tb.querySelector('tbody');
            if(vthis.classList.contains('rotate-180')){
                vthis.classList.remove('rotate-180');
                tbody.hidden = false;
            }else{
                vthis.classList.add('rotate-180');
                tbody.hidden = true;
            }
        }

    // 취소하기/
    function teachAfUAddCancel(vthis){

        teachAfUAddClear();
        return;

        const msg =
         `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
            <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">사용자 등록을 취소하시겠습니까?</p>
            </div>
        `;
        sAlert('', msg, 3, function(){
            //선택 table 삭제 후
            const tb = vthis.closest('[data-tb]');
            tb.remove();

             /* 카운트해서 순서대로 index를 다시 부여한다. */
            const tb_idx = document.querySelectorAll('[data-tb');
            tb_idx.forEach(function(tb, idx){
                tb.setAttribute('data-tb', idx);
                const user_index_el = tb.querySelector('[data-user-index]');
                user_index_el.innerText = (idx*1) + 1;
            });
        });
    }

    // 인쇄하기
    function teachAfUAddPrint(){

    }

    // 오늘 등록 내역 리스트 불러오기.
    function teachAfUAddTodayList(){
        teachAfUAddStudentSelect('', function(result){
            if((result.resultCode||'') == 'success'){
                //초기화.
                const bundle = document.querySelector('[data-bundle="today_insert_students"]');
                const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                bundle.innerHTML = '';
                bundle.appendChild(row_copy);

                const students = result.class_mates;
                students.forEach(function(student, idx){
                    const row = row_copy.cloneNode(true);
                    row.hidden = false;
                    row.setAttribute('data-row', 'clone');
                    row.querySelector('[data-tb-index]').innerText = idx+1;
                    row.querySelector('[data-student-seq]').value = student.student_seq;
                    row.querySelector('[data-student-name]').innerText = student.student_name;
                    bundle.appendChild(row);
                });
            }else{}
        });

    }

    // 학생 내역 불러오기.(오늘 등록 및 선택 학생)
    function teachAfUAddStudentSelect(student_seq, callback){
        const teach_seq = document.querySelector('[data-main-teach-seq]').value;
        const team_code = document.querySelector('[data-main-team-code]').value;

        const page = "/teacher/main/after/class/student/select";
        const parameter = {
            teach_seq_post: teach_seq,
            team_code: team_code,
            student_seq: student_seq,
            search_type: 'is_today',
            no_class:"Y",
            is_add_coulmn: "Y"
        };

        queryFetch(page, parameter, function(result){
            if(callback){
                callback(result);
            }
        });
    }

    // 정보 가져와서 넣어주기.
    function teachAfUAddInfoSelect(vthis){
        const tr = vthis.closest('tr');
        const student_seq = tr.querySelector('[data-student-seq]').value;

        teachAfUAddStudentSelect(student_seq, function(result){
            if((result.resultCode||'') == 'success'){
                const student = result.class_mates[0];
                document.querySelector('[data-edit-student-seq]').value = student_seq;
                document.querySelector('[data-student-name]').value = student.student_name;
                document.querySelector('[data-parent-phone]').value = student.parent_phone;
                document.querySelector('[data-grade-name]').value = student.grade_seq||'';
                document.querySelector('[data-student-st-class-name]').value = student.class_name||'';
                document.querySelector('[data-class-seq]').value = student.class_seq||'';
                document.querySelector('[data-class-start-date]').value = student.class_start_date;
                document.querySelector('[data-class-start-date]').oninput();
                document.querySelector('[data-class-end-date]').value = student.class_end_date;
                document.querySelector('[data-class-end-date]').oninput();

                document.querySelector('[data-student-tmp-id]').innerText = student.student_id;
                document.querySelector('[data-student-tmp-pw]').innerText = '*****';


                //저장하기 버튼 숨김처리, 취소하기 버튼 보이기 처리.
                const btn_save = document.querySelector('[data-btn-save]');
                const btn_cancel = document.querySelector('[data-btn-cancel]');
                const btn_delete = document.querySelector('[data-btn-delete]');
                btn_save.hidden = true;
                btn_cancel.hidden = false;
                btn_delete.hidden = false;

                //스크롤 top 200px 이동
                window.scrollTo({top: 200, behavior: 'smooth'});


            }
        });

    }

    // 취소하기.리로드
    function teachAfUAddDelete(){
        const student_seq = document.querySelector('[data-edit-student-seq]').value;
        const team_code = document.querySelector('[data-main-team-code]').value;

         const msg =
        `   <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
            <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">등록하신 학생을 삭제하시겠습니까?</p>
            </div>
        `;
        sAlert('', msg, 3, function(){
            const page = "/teacher/after/users/delete";
            const parameter = {
                grouptype:'student',
                user_key: student_seq,
                team_code: team_code,
                is_delete_parent: 'Y'
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    const msg =
                    `
                        <div class="text-m-28px">학생이 삭제되었습니다.</div>
                    `;
                        setTimeout(function(){
                            sAlert('',msg, 4,function(){
                                teachAfUAddClear();
                            });
                        }, 1000);

                }else{}
            });
        });
    }

    </script>
@endsection


