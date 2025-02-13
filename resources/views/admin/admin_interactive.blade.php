@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
인터렉티브 관리
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<div class="col-12 pe-3 ps-3 position-relative">
    <section data-main-section="1">
        <div class="row mx-0">
            <input class="form-control col-auto w-50 me-3" placeholder="조회할 제목을 입력해주세요." data-search-title>
            <div class="row mx-0 col-auto gap-1 me-3">
                <select class="col form-select" data-search-subject-code>
                    <option value="">과목전체</option>
                    @if(!empty($subject_codes))
                    @foreach($subject_codes as $subject_code)
                    <option value="{{$subject_code->id}}">{{$subject_code->code_name}}</option>
                    @endforeach
                    @endif
                </select>
                <select class="col form-select" data-search-grade-code >
                    <option value="">학년전체</option>
                    @if(!empty($grade_codes))
                    @foreach($grade_codes as $grade_code)
                    <option value="{{$grade_code->id}}">{{$grade_code->code_name}}</option>
                    @endforeach
                    @endif
                </select>
                <select class="col form-select" data-search-semester-code >
                    <option value="">학기전체</option>
                    @if(!empty($semester_codes))
                    @foreach($semester_codes as $semester_code)
                    <option value="{{$semester_code->id}}">{{$semester_code->code_name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <button class="btn btn-primary col-auto me-1" onclick="interSelect();">조회</button>
            <button class="btn btn-primary col-auto" onclick="interAddModalShow('insert');">등록</button>
        </div>

        <div class="mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>과목</th>
                        <th>학년</th>
                        <th>학기</th>
                        <th>타입</th>
                        <th>제목</th>
                        <th>등록날짜</th>
                        <th>수정날짜</th>
                        <th>작성자</th>
                        <th>수정자</th>
                        <th>기능</th>
                    </tr>
                </thead>
                <tbody data-bundle="inter_list">
                    <tr data-row="copy" hidden>
                        <input type="hidden" data-interactive-seq>
                        <input type="hidden" data-subject-seq>
                        <input type="hidden" data-grade-seq>
                        <input type="hidden" data-semester-seq>

                        <td data-subject-name></td>
                        <td data-grade-name></td>
                        <td data-semester-name></td>
                        <td data-type></td>
                        <td data-title></td>
                        <td data-created-at></td>
                        <td data-updated-at></td>
                        <td data-created-name></td>
                        <td data-updated-name></td>
                        <td>
                            <textarea data-json-data hidden></textarea>
                            <button class="btn btn-primary" onclick="interAddModalShow('update', this)">수정</button>
                            <button class="btn btn-primary" onclick="interAddModalShow('detail', this);">상세</button>
                            <button class="btn btn-danger" onclick="interDelete(this);">삭제</button>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
        <div class="all-center mt-52">
            <div class=""></div>
            <div class="col-auto">
                {{-- 페이징 --}}
                <div class="col d-flex justify-content-center">
                    <ul class="pagination col-auto" data-page="1" hidden>
                        <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1"
                            onclick="userPaymentPageFunc('1', 'prev')">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                        </button>
                        <li class="page-item" hidden>
                            <a class="page-link" onclick="">0</a>
                        </li>
                        <span class="page" data-page-first="1" hidden onclick="userPaymentPageFunc('1', this.innerText);"
                            disabled>0</span>
                        <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1"
                            onclick="userPaymentPageFunc('1', 'next')" data-is-next="0">
                            <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                        </button>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>

{{--  모달 / 문제 등록 --}}
<div class="modal fade" id="modal_inter_add" tabindex="-1" aria-hidden="true" style="display: none;">
    <input type="hidden" data-interactive-seq >
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" data-modal-title>문제 타이틀 등록</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body">
                <div class="row mx-0 gap-1">
                    <select class="col form-select" data-subject-code >
                        <option value="">과목전체</option>
                        @if(!empty($subject_codes))
                        @foreach($subject_codes as $subject_code)
                        <option value="{{$subject_code->id}}">{{$subject_code->code_name}}</option>
                        @endforeach
                        @endif

                    </select>
                    <select class="col form-select" data-grade-code >
                        <option value="">학년전체</option>
                        @if(!empty($grade_codes))
                        @foreach($grade_codes as $grade_code)
                        <option value="{{$grade_code->id}}">{{$grade_code->code_name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <select class="col form-select" data-semester-code >
                        <option value="">학기전체</option>
                        @if(!empty($semester_codes))
                        @foreach($semester_codes as $semester_code)
                        <option value="{{$semester_code->id}}">{{$semester_code->code_name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="row mx-0 mt-2">
                    <input class="form-control" placeholder="제목을 입력해주세요." data-title >
                    {{-- <input class="form-control mt-2" placeholder="타입을 입력해주세요."  data-type> --}}
                    <input type="text" class="form-select mt-2" data-type value="" />
                    <input type="file" class="form-control mt-2" onchange="interJsonFileUpload(this)" placeholder="json파일을 올려보아요" data-json-file style="height: 200px;">
                    <textarea class="form-control mt-2" name="" cols="30" rows="10" data-json-data placeholder="json을 입력해주세요."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="interInsert();" data-btn-inter-add>등록</button>
                <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal" onclick="">닫기</button>
            </div>
        </div>
    </div>
</div>

{{--  모달 / json_data 상세 보기 --}}
<div class="modal fade" id="modal_inter_detail" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">문제 상세</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body">
                <div class="row mx-0 mt-2">
                    <div class="form-control" name="" cols="30" rows="10" data-json-data ></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                    onclick="">닫기</button>
            </div>
        </div>
    </div>

<script>

document.addEventListener('DOMContentLoaded', function(){
    interSelect();
});

// 인터렉티브 모달 보기
function interAddModalShow(type, vthis){
    const myModal = new bootstrap.Modal(document.getElementById('modal_inter_add'), {
        keyboard: false
    });
    myModal.show();

        // 수정일때는 seq를 넣어준다.
    const modal = document.querySelector('#modal_inter_add');
    if(type == 'insert'){
        modal.querySelector('[data-interactive-seq]').value = '';
        modal.querySelector('[data-subject-code]').value = '';
        modal.querySelector('[data-grade-code]').value = '';
        modal.querySelector('[data-semester-code]').value = '';
        modal.querySelector('[data-title]').value = '';
        modal.querySelector('[data-json-data]').value = '';
        modal.querySelector('[data-type]').value = '';
        modal.querySelector('[data-modal-title]').innerText = '인터렉티브 등록';
        modal.querySelector('[data-btn-inter-add]').innerText = '인터렉티브 등록';
    }else if(type == 'update'){
        const tr = vthis.closest('tr');
        // 시험 수정시 모달 세팅
        interAddModalSetting(tr);
        modal.querySelector('[data-modal-title]').innerText = '인터렉티브 수정';
        modal.querySelector('[data-btn-inter-add]').innerText = '인터렉티브 수정';
    }

}

// 인터렉티브 수정시 모달 세팅
function interAddModalSetting(tr){
    const modal = document.querySelector('#modal_inter_add');
    const interactive_seq = tr.querySelector('[data-interactive-seq]').value;
    modal.querySelector('[data-interactive-seq]').value = interactive_seq;
    // select 의 text 를 가지고 선택
    modal.querySelector('[data-subject-code]').value = tr.querySelector('[data-subject-seq]').value;
    modal.querySelector('[data-grade-code]').value = tr.querySelector('[data-grade-seq]').value;
    modal.querySelector('[data-semester-code]').value = tr.querySelector('[data-semester-seq]').value;
    modal.querySelector('[data-title]').value = tr.querySelector('[data-title]').innerText;
    modal.querySelector('[data-json-data]').value = tr.querySelector('[data-json-data]').value;
    modal.querySelector('[data-type]').value = tr.querySelector('[data-type]').innerText;

}

// 인터렉티브 등록
function interInsert(){
    const msg = "<div class='text-sb-24px'>인터렉티브를 등록 하시겠습니까?</div>"
    const modal = document.querySelector('#modal_inter_add');

    const interactive_seq = modal.querySelector('[data-interactive-seq]').value;
    const subject_seq = modal.querySelector('[data-subject-code]').value;
    const grade_seq = modal.querySelector('[data-grade-code]').value;
    const semester_seq = modal.querySelector('[data-semester-code]').value;
    const title = modal.querySelector('[data-title]').value;
    const type = modal.querySelector('[data-type]').value;
    const json_data = modal.querySelector('[data-json-data]').value;

    const page = "/manage/interactive/insert";
    const parameter = {
        interactive_seq:interactive_seq,
        subject_seq: subject_seq,
        grade_seq: grade_seq,
        semester_seq: semester_seq,
        title: title,
        type: type,
        json_data: json_data
    };
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                if(interactive_seq == '')
                    toast('인터렉티브가 등록 되었습니다.');
                else
                    toast('인터렉티브가 수정 되었습니다.');

                // 모달 닫기.
                //modal.querySelector('.modal_close').click();

                // 문제 리스트 가져오기.
                interSelect();

            }else{}
        });
    });
}

// 인터렉티브 조회.
function interSelect(page_num){
    const title = document.querySelector('[data-search-title]').value;
    const subject_code = document.querySelector('[data-search-subject-code]').value;
    const grade_code = document.querySelector('[data-search-grade-code]').value;
    const semester_code = document.querySelector('[data-search-semester-code]').value;

    const page = "/manage/interactive/select";
    const parameter = {
        title: title,
        subject_code: subject_code,
        grade_code: grade_code,
        semester_code: semester_code,
        page:page_num,
    };

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 초기화
            const bundle = document.querySelector('[data-bundle="inter_list"]');
            const row_copy = bundle.querySelector('[data-row="copy"]');
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            const interactives = result.interactives;
            // 페이징
            userPaymentTablePaging(result.interactives, '1');

            // foreach
            interactives.data.forEach(function(result){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.dataset.row='clone';
                row.querySelector('[data-subject-name]').innerText = result.subject_name||'';
                row.querySelector('[data-grade-name]').innerText = result.grade_name||'';
                row.querySelector('[data-semester-name]').innerText = result.semester_name||'';
                row.querySelector('[data-type]').innerText = result.type;
                row.querySelector('[data-title]').innerText = result.title;
                row.querySelector('[data-json-data]').value = result.json_data;
                row.querySelector('[data-created-at]').innerText = new Date(result.created_at).toLocaleString('ko-KR', {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute:'2-digit', second:'2-digit'}).replace(/\. /g, '.').replace(/:/g, '.').replace(/ /g, '.');
                row.querySelector('[data-updated-at]').innerText = new Date(result.updated_at).toLocaleString('ko-KR', {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute:'2-digit', second:'2-digit'}).replace(/\. /g, '.').replace(/:/g, '.').replace(/ /g, '.');
                row.querySelector('[data-created-name]').innerText = result.created_name;
                row.querySelector('[data-updated-name]').innerText = result.updated_name;
                row.querySelector('[data-subject-seq]').value = result.subject_seq;
                row.querySelector('[data-grade-seq]').value = result.grade_seq;
                row.querySelector('[data-semester-seq]').value = result.semester_seq;
                row.querySelector('[data-interactive-seq]').value = result.id;
                bundle.appendChild(row);
            });
        }
    });
}

// 페이징 함수
function userPaymentTablePaging(rData, target){
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

// 페이지 번호 클릭
function userPaymentPageFunc(target, type){
    if(type == 'next'){
        const page_next = document.querySelector(`[data-page-next="${target}"]`);
        if(page_next.getAttribute("data-is-next") == '0') return;
        // data-page 의 마지막 page_num 의 innerText를 가져온다
        const last_page = document.querySelector(`[data-page="${target}"] .page_num:last-of-type`).innerText;
        const page = parseInt(last_page) + 1;
        if(target == "1")
            interSelect(page);
    }
    else if(type == 'prev'){
        // [data-page-first]  next tag 의 innerText를 가져온다
        const page_first = document.querySelector(`[data-page-first="${target}"]`);
        const page = page_first.innerText;
        if(page == 1) return;
        const page_num = page*1 -1;
        if(target == "1")
            interSelect(page);
    }
    else{
        if(target == "1")
            interSelect(type);
    }
}

// json 파일 업로드
function interJsonFileUpload(input){
    const file = input.files[0];
    const textArea = input.closest('div').querySelector('[data-json-data]');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const jsonContent = e.target.result;
            let jsonData;
            if(Array.isArray(JSON.parse(jsonContent))){
                jsonData = JSON.parse(jsonContent)[0];
            }else{
                jsonData = JSON.parse(jsonContent);
            }
            console.log(jsonData);
            textArea.value = JSON.stringify(jsonData);
            const subjectSelect = document.querySelector('[data-subject-code]');
            const subjectOptions = subjectSelect.options;
            for (let i = 0; i < subjectOptions.length; i++) {
                if (subjectOptions[i].text === jsonData.subject) {
                    subjectSelect.selectedIndex = i;
                    break;
                }
            }
            const gradeSelect = document.querySelector('[data-grade-code]');
            const gradeOptions = gradeSelect.options;
            for (let i = 0; i < gradeOptions.length; i++) {
                if (gradeOptions[i].text === jsonData.grade + '학년') {
                    gradeSelect.selectedIndex = i;
                    break;
                }
            }
            const semesterSelect = document.querySelector('[data-semester-code]');
            const semesterOptions = semesterSelect.options;
            for (let i = 0; i < semesterOptions.length; i++) {
                if (semesterOptions[i].text === jsonData.semester + "학기") {
                    semesterSelect.selectedIndex = i;
                    break;
                }
            }

            document.querySelector('.form-control[data-title]').value = file.name;
            const typeSelect = document.querySelector('.form-select[data-type]');
            if (jsonData.detail.summary?.title_kor == "정리 하기") {
                typeSelect.value = "정리학습"; // 정리학습
                console.log('정리학습');
            } else {
                typeSelect.value = "개념다지기"; // 개념다지기
                console.log('개념다지기');
            }

        };
        reader.readAsText(file, 'utf-8');
    }
}
</script>
@endsection

