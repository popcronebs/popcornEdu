@extends('layout.layout')

@section('head_title')
학생정보관리
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<input type="hidden" data-main-team-code value="{{ $team_code }}">
<input type="hidden" data-main-teacher-seq value="{{ $teach_seq }}">
<input type="hidden" data-main-region-seq value="{{ $region_seq }}">

<div class="sub-title zoom_sm">
    <h2 class="text-sb-42px">
    <img src="{{ asset('images/svg/calendar_in_user_icon.svg?1') }}" width="72">
        학생 정보관리
    </h2>
</div>

<section class="zoom_sm">
    <div class="d-flex justify-content-between align-items-end mb-32">
        <div>
            <label class="d-inline-block select-wrap select-icon">
                <select id="sel_class_seq" onchange="teachStAfterStudentSelect();"
                    class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                    <option value="">전체 클래스</option>
                    @if(!empty($classes))
                        @foreach($classes as $class)
                            <option value="{{ $class->id}}">{{ $class->class_name }}</option>
                        @endforeach
                    @endif
                </select>
            </label>
        </div>
        <div class="h-center">
            <!-- 날짜를 검색해서 무엇을 할지에 대해서 알수가 없으므로 숨기처리. -->
            <label class="d-inline-block select-wrap " style="display: none;"> <!-- select-icon -->
                <select id="select2" hidden
                    onchange="teachStAfterSelectDateType(this, '[data-search-start-date]','[data-search-end-date]'); teachStAfterStudentSelect();"
                    class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                    <option value="">기간설정</option>
                    <option value="-1">오늘로보기</option>
                    <option value="0">1주일전</option>
                    <option value="1">1개월전</option>
                    <option value="2">3개월전</option>
                </select>
            </label>
            <div class="h-center p-3 border rounded-pill" hidden>
                <img src="{{ asset('images/calendar_gray_icon.svg') }}" class="me-2">
                <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start" style="height: 20px;">
                    <div class="h-center justify-content-between">
                        <div data-date
                            onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                            type="text" class="text-m-20px text-start scale-text-gray_05" readonly=""
                            placeholder="">
                            {{-- 상담시작일시 --}}
                            {{ date('Y.m.d') }}
                        </div>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                    </div>
                    <input type="date" style="width: 80px;height: 0.5px;" data-search-start-date
                        oninput="teachStAfterDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                </div>
                ~
                <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start"
                    style="height: 20px;">
                    <div class="h-center justify-content-between">
                        <div data-date
                            onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                            type="text" class="text-m-20px text-start scale-text-gray_05" readonly=""
                            placeholder="">
                            {{-- 상담시작일시 --}}
                            {{ date('Y.m.d') }}
                        </div>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                    </div>
                    <input type="date" style="width: 80px;height: 0.5px;" data-search-end-date
                        oninput="teachStAfterDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                </div>

            </div>
            <div class="d-flex ms-3">
                <div class="d-inline-block select-wrap select-icon me-12">
                    <select data-select-teach-st-after-search-type="1" onchange="teachStAfterStudentSelect();"
                        class="rounded-pill border-gray lg-select text-sb-20px">
                        <option value="">검색기준</option>
                        <option value="grade_name">학년</option>
                        <option value="name">이름</option>
                        <option value="student_id">아이디</option>
                        <option value="class_name">방과후 클래스</option>
                        <option value="parent_name">학부모 이름</option>
                    </select>
                </div>
                <!-- :검색  -->
                <label class="label-search-wrap">
                    <input type="text" data-inp-teach-st-after-search-str="1"
                        onkeyup="teachStAfterStudentSelect();"
                        class="ms-search border-gray rounded-pill text-m-20px w-100" placeholder="단어를 검색해보세요.">
                </label>
            </div>
        </div>
    </div>

    <div class="row justify-content-md-center">
        <div class="col-12 ">
            <div>
            <table class="table-style w-100" style="min-width: 100%;">
                <colgroup>
                    <col style="width: 80px;">
                </colgroup>
                <thead class="">
                    <tr class="text-sb-20px modal-shadow-style rounded">
                        <th style="width: 80px" class="td_checkbox">
                            <label class="checkbox mt-1">
                                <input type="checkbox" id="inp_all_chk"  onchange="teachStAfterAllChk(this)">
                                <span class="">
                                </span>
                            </label>
                        </th>
                        <th>학년/반</th>
                        <th>방과후 클래스</th>
                        <th>이름/아이디</th>
                        <th>학생 전화번호</th>
                        <th>학부모</th>
                        <th>학부모 전화번호</th>
                        <th>이용 활성화</th>
                        <th class="td_checkbox">더보기</th>
                    </tr>
                </thead>
                <tbody data-bundle="tby_students">
                    <tr class="text-m-20px h-104" data-row="copy" hidden>
                        <input type="hidden" data-student-seq>
                        <input type="hidden" data-class-seq>
                        <td class="td_checkbox" >
                            <label class="checkbox mt-1">
                                <input type="checkbox" class="chk" onchange="teachStAfterChkInput(this)">
                                <span class="">
                                </span>
                            </label>
                        </td>
                        <td class="scale-text-gray_05" data-explain="학년/반">
                            <span data-grade-name></span>/
                            <span data-st-class-name></span>
                        </td>
                        <td class="scale-text-gray_05">
                            <span data-class-name data-explain="방과후 클래스"></span>
                        </td>
                        <td class="sale-text-gray_05" data-explain="이름/아이디">
                            <span data-student-name></span>/
                            <span data-student-id></span>

                        </td>
                        <td class="scale-text-gray_05" data-student-phone data-explain="학생 전화번호">
                        </td>
                        <td class="scale-text-gray_05"  data-explain="학부모이름/아이디">
                            <span data-parent-name></span>
                            (<span data-parent-id></span>)
                        </td>
                        <td class="scale-text-gray_05" data-parent-phone data-explain="학부모 전화번호"> </td>
                        <td class="scale-text-gray_05 cursor-pointer" data-explain="이용 활성화">
                            <label class="toggle">
                                <input type="checkbox" class="" data-is-use checked=""
                                    onchange="teachStAfterStIsUseUpdate(this)">
                                <span class=""></span>
                            </label>
                        </td>
                        <td class="scale-text-gray_05 td_checkbox">
                            <button type="button" onclick="teachStAfterUserEditPage(this);"
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">상세보기</button>
                        </td>
                    </tr>

                </tbody>
            </table>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-52">
        <div class="col">
            {{-- <button type="button"
                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2">
                SMS 문자/알림톡
            </button> --}}
        </div>
        <div class="my-custom-pagination col">
            <div class="col d-flex justify-content-center">
                <ul class="pagination col-auto" data-page="1" hidden>
                    <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1" onclick="teachStAfterPageFunc('1', 'prev')" disabled>
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                    </button>
                    <li class="page-item" hidden>
                        <a class="page-link" onclick="">0</a>
                    </li>
                    <span class="page" data-page-first="1" hidden onclick="teachStAfterPageFunc('1', this.innerText);" disabled>0</span>
                    <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1" onclick="teachStAfterPageFunc('1', 'next')">
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" style="transform: rotate(180deg);" alt="">
                    </button>
                </ul>
            </div>
        </div>
        <div class="col text-end">
            <button type="button" onclick="teachStAfterlistExcelDownload()"
                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" class="me-1">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M10.8649 16.6265C10.2489 16.6265 9.74959 16.1271 9.74959 15.5111V10.8574L6.87216 10.8574C5.84408 10.8574 5.33501 9.60924 6.06985 8.89023L11.1707 3.89928C11.6166 3.46299 12.3294 3.46299 12.7753 3.89928L17.8762 8.89024C18.611 9.60924 18.1019 10.8574 17.0739 10.8574L14.166 10.8574V15.5111C14.166 16.1271 13.6667 16.6265 13.0507 16.6265H10.8649Z"
                        fill="#DCDCDC"></path>
                    <rect x="5.57031" y="17.8208" width="12.8027" height="1.75074" rx="0.875369"
                        fill="#DCDCDC"></rect>
                </svg>
                Excel 내보내기
            </button>
            {{-- <button type="button" onclick="teachManMovePageUsersAdd();"
                class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white text-dark px-3 me-2 align-bottom">
                <svg class="m-1" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M10.8654 3.57031C10.2494 3.57031 9.75008 4.06966 9.75008 4.68565V9.33936L6.87265 9.33936C5.84457 9.33936 5.3355 10.5875 6.07034 11.3065L11.1712 16.2975C11.6171 16.7338 12.3299 16.7338 12.7758 16.2975L17.8767 11.3065C18.6115 10.5875 18.1024 9.33936 17.0744 9.33936L14.1665 9.33936V4.68565C14.1665 4.06966 13.6671 3.57031 13.0512 3.57031H10.8654Z"
                        fill="#222222"></path>
                    <rect x="5.57031" y="17.8203" width="12.8027" height="1.75074" rx="0.875369" fill="#222222">
                    </rect>
                </svg>
                사용자 불러오기
            </button> --}}

            <button onclick="teachManMovePageUsersAdd('/teacher/after/users/add/list')";
            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom"> 사용자 개별 등록 </button>
        </div>
    </div>
</section>

<div data-explain="160px">
   <div class="py-lg-5"></div>
   <div class="py-lg-4"></div>
   <div class="pt-lg-3"></div>
</div>

<form action="/teacher/student/after/detail" method="post" data-form-student-info-detail hidden>
    @csrf
    <input type="hidden" name="student_seq">
    <input type="hidden" name="class_seq">
</form>

{{-- 등록 페이지 불러오기. --}}
<form action="/teacher/users/add/excel" data-form-user-add-excel hidden>
    @csrf
    <input name="user_type">
    <input name="region_seq">
    <input name="team_code">
</form>

<script>

    document.addEventListener('DOMContentLoaded', function(){
        teachStAfterStudentSelect();
    });

    document.addEventListener('visibilitychange', function(event) {
    if (sessionStorage.getItem('isBackNavigation') === 'true') {
        console.log('뒤로 가기 버튼을 클릭한 후 페이지가 로드되었습니다.');
        // 여기에 뒤로 가기 버튼을 클릭한 후 페이지가 로드되었을 때 실행할 코드를 작성합니다.
        sessionStorage.removeItem('isBackNavigation'); // 상태를 초기화합니다.

        teachStAfterStudentSelect();
    }
  });

    //담당 선생님이 담당하는 학생 불러오기
    function teachStAfterStudentSelect(page_num){
        const teach_seq = document.querySelector('[data-main-teacher-seq]').value;
        const page = "/teacher/main/after/class/student/select";
        const search_type = document.querySelector('[data-select-teach-st-after-search-type="1"]').value;
        const search_str = document.querySelector('[data-inp-teach-st-after-search-str="1"]').value;
        const class_seq = document.querySelector('#sel_class_seq').value;
        let no_class = 'Y';
        if(class_seq != ''){
            no_class = 'N';
        }

        const parameter = {
            no_class:no_class,
            is_add_coulmn:"Y",
            teach_seq_post:teach_seq,
            search_type:search_type,
            search_str:search_str,
            is_page:"Y",
            class_seq:class_seq,
            page:page_num||1,
        }
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                document.querySelector('#inp_all_chk').checked = false;
                //* 학생정보 data-bundle="tby_students" data-row="copy"
                // 테이블 초기화 후 클론 row 넣어주기
                const bundle = document.querySelector('[data-bundle="tby_students"]');
                const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                bundle.innerHTML = '';
                bundle.appendChild(copy_row);

                const students = result.class_mates ;

                teachStAfterTablePaging(students, '1');

                students.data.forEach(function(student){
                    const row = copy_row.cloneNode(true);
                    row.setAttribute('data-row', 'clone');
                    row.hidden = false;
                    row.querySelector('[data-student-seq]').value = student.student_seq;
                    row.querySelector('[data-grade-name]').innerText = student.grade_name;
                    row.querySelector('[data-st-class-name]').innerText = student.class_name;
                    row.querySelector('[data-class-name]').innerText = student.cl_class_name;
                    row.querySelector('[data-class-seq]').value = student.class_seq;
                    row.querySelector('[data-student-name]').innerText = student.student_name;
                    row.querySelector('[data-student-id]').innerText = student.student_id;
                    row.querySelector('[data-student-phone]').innerText = student.student_phone;
                    row.querySelector('[data-parent-name]').innerText = student.parent_name;
                    row.querySelector('[data-parent-id]').innerText = student.parent_id;
                    row.querySelector('[data-parent-phone]').innerText = student.parent_phone;
                    row.querySelector('[data-is-use]').checked = student.is_use == 'Y' ? true : false;
                    bundle.appendChild(row);
                    if(chks[student.student_seq]){
                        row.querySelector('.chk').checked = true;
                    }
                });

            }else{}
        });
    }

    // 학생 활성화 변경
    function teachStAfterStIsUseUpdate(vthis) {
        const tr = vthis.closest('tr');
        const student_seq = tr.querySelector('[data-student-seq]').value;
        const is_use = vthis.checked ? 'Y' : 'N';

        const page = '/manage/userlist/user/use/update';
        const parameter = {
            user_key: student_seq,
            group_type: 'student',
            chk_val: is_use,
        };
        queryFetch(page, parameter, function(result) {
            if (result.resultCode == '1' || result.resultCode == 'success') {
                toast('변경되었습니다.');
            }
        });
    }

    // 엑셀 내보내기
    // function teachStAfterlistExcelDownload(){
    //     const tby_table = document.querySelector('[data-bundle="tby_students"]');
    //     const pt_div_tby = tby_table.closest('div');
    //     const clone_tag = pt_div_tby.parentElement.cloneNode(true);
    //     //안에 hidden = true인 태그는 제거
    //     clone_tag.querySelectorAll('tr').forEach((item)=>{
    //         if(item.hidden)
    //             item.remove();
    //         //tr 안에 태그중 style display:none인 태그는 제거
    //         //tr 안에 태그중 type=hidden 인 태그 제거
    //         //tr 안에 태그중 hidden = true인 태그는 제거
    //         //checkbox 제거
    //         item.querySelectorAll('[style*="display:none"], button, [type="hidden"], [hidden], input[type=checkbox]').forEach((item2)=>{
    //             item2.remove();
    //         });
    //         //radio 는 Y,N 으로 span 글자로 변경 후 삭제
    //         item.querySelectorAll('input[type=checkbox]').forEach((item2)=>{
    //             if(item2.checked)
    //                 item2.insertAdjacentHTML('afterend', '<span>Y</span>');
    //             else
    //                 item2.insertAdjacentHTML('afterend', '<span>N</span>');
    //             item2.remove();
    //         });
    //     });
    //     const html = clone_tag.outerHTML;
    //     _excelDown('방과후학생목록.xls', '방과후학생목록', html);
    // }
    // 엑셀로 내보내기
    function teachStAfterlistExcelDownload(){
        // 체크가 있는지 확인
        const keys = Object.keys(chks);
        if(keys.length < 1){
            toast('선택된 회원이 없습니다.');
            return;
        }
        const table = document.querySelector('[data-bundle="tby_students"]').closest('table').cloneNode(true);
        const bundle = table.querySelector('[data-bundle="tby_students"]');
        bundle.innerHTML = '';
        // keys 반대 정렬.
        keys.reverse();
        keys.forEach(function(key){
            bundle.innerHTML += chks[key].outer_html;
        });
        // td 체크박스 삭제.
        table.querySelectorAll('.td_checkbox').forEach(function(td){
            td.remove();
        });

        bundle.querySelectorAll('tr').forEach((item)=>{
            if(item.hidden)
            item.remove();
            //tr 안에 태그중 style display:none인 태그는 제거
            //tr 안에 태그중 type=hidden 인 태그 제거
            //tr 안에 태그중 hidden = true인 태그는 제거
            //checkbox 제거
            item.querySelectorAll('[style*="display:none"], button, [type="hidden"], [hidden]').forEach((item2)=>{
                item2.remove();
            });
            //radio 는 Y,N 으로 span 글자로 변경 후 삭제
            item.querySelectorAll('input[type=checkbox]').forEach((item2)=>{
                if(item2.checked)
                item2.insertAdjacentHTML('afterend', '<span>Y</span>');
                else
                item2.insertAdjacentHTML('afterend', '<span>N</span>');
                item2.remove();
            });
        });

        const html = table.outerHTML;
        _excelDown('학생목록.xls', '학생목록', html);
    }

    // 학생정보 상세보기
    function teachStAfterUserEditPage(vthis){
        const tr = vthis.closest('tr');
        const student_seq = tr.querySelector('[data-student-seq]').value;
        const class_seq = tr.querySelector('[data-class-seq]').value;
        //form 을 만들어서 teach_seq 넣고 submit
        const form = document.querySelector('[data-form-student-info-detail]');
        form.querySelector('[name="student_seq"]').value = student_seq;
        form.querySelector('[name="class_seq"]').value = class_seq;
        form.method = 'post';
        form.target = '_self';
        form.submit();
    }

    // 엑셀 일괄 등록 /  등록 페이지 이동.
    function teachManMovePageUsersAdd(url){
        //data-form-user-add-excel 에 user_type, region_seq, team_code 넣기.
        const form = document.querySelector('[data-form-user-add-excel]');
        const user_type = 'student';
        const team_code = document.querySelector('[data-main-team-code]').value;
        const region_seq = document.querySelector('[data-main-region-seq]').value;

        form.querySelector('[name="user_type"]').value = user_type
        form.querySelector('[name="team_code"]').value = team_code;
        form.querySelector('[name="region_seq"]').value = region_seq;

        if(url) form.action = url;
        else form.action = '/teacher/users/add/excel';

        form.method = 'post';
        form.target = '_self';
        form.submit();
    }

    // 페이징  함수
    function teachStAfterTablePaging(rData, target){
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

        //페이지 펑션
    function teachStAfterPageFunc(target, type) {
        const isNext = type.toString() == 'next';
        const isPrev = type.toString() == 'prev';
        const page_length = document.querySelectorAll(`span.page.page_num`).length;
        const lastPage = parseInt(document.querySelector(`[data-page-next]`).getAttribute("data-page-next"));
        const firstPage = parseInt(document.querySelector(`[data-page-prev]`).getAttribute("data-page-prev"));
        const page_num = parseInt(document.querySelector(`.page.page_num.active`).innerText);
        if (isNext) {
            const nextPage = lastPage + page_num;
            teachStAfterStudentSelect(nextPage);
            if(nextPage == page_length){
                document.querySelector(`[data-page-next]`).setAttribute("disabled", "disabled");
            }else{
                document.querySelector(`[data-page-next]`).removeAttribute("disabled");
            }
            if(nextPage == 1){
                document.querySelector(`[data-page-prev]`).setAttribute("disabled", "disabled");
            }else{
                document.querySelector(`[data-page-prev]`).removeAttribute("disabled");
            }
        } else if (isPrev) {
            const nextPage = page_num - lastPage;
            teachStAfterStudentSelect(nextPage);
            if(nextPage == firstPage){
                document.querySelector(`[data-page-prev]`).setAttribute("disabled", "disabled");
            }else{
                document.querySelector(`[data-page-prev]`).removeAttribute("disabled");
            }
            if(nextPage == page_length){
                document.querySelector(`[data-page-next]`).setAttribute("disabled", "disabled");
            }else{
                document.querySelector(`[data-page-next]`).removeAttribute("disabled");
            }
        } else {
            if (target === "1") {
                teachStAfterStudentSelect(type);
            }
            if(type == 1){
                document.querySelector(`[data-page-prev]`).setAttribute("disabled", "disabled");
            }else{
                document.querySelector(`[data-page-prev]`).removeAttribute("disabled");
            }

            if(type == page_length){
                document.querySelector(`[data-page-next]`).setAttribute("disabled", "disabled");
            }else{
                document.querySelector(`[data-page-next]`).removeAttribute("disabled");
            }
        }

    }

    // 기간설정 select onchange
    function teachStAfterSelectDateType(vthis, start_date_tag, end_date_tag) {
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
    function teachStAfterDateTimeSel(vthis) {
        //datetime-local format yyyy.MM.dd HH:mm 변경
        const date = new Date(vthis.value);
        vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = date.format('yyyy.MM.dd')
    }

    let chks = {};
    // 체크박스 체크
    function teachStAfterChkInput(vthis){
        const tr = vthis.closest('tr');
        const student_seq = tr.querySelector('[data-student-seq]').value;
        if(vthis.checked){
            chks[student_seq] = {
                student_seq:student_seq,
                outer_html:tr.outerHTML
            };
        }else{
            delete chks[student_seq];
        }
    }

    // 현제 패이지 전체 체크 박스 체크
    function teachStAfterAllChk(vthis){
        const bundle = document.querySelector('[data-bundle="tby_students"]');
        const rows = bundle.querySelectorAll('[data-row="clone"]');
        const checked = vthis.checked;
        rows.forEach(function(row){
            row.querySelector('.chk').checked = checked;
            row.querySelector('.chk').onchange();
        });
    }
</script>
@endsection
