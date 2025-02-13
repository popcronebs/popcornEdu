@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '알림센터')

@section('layout_coutent')
<div class="">
    <div class="sub-title d-flex justify-content-between">
        <h2 class="text-sb-42px">
            <img src="{{ asset('images/big_bell_icon.svg') }}" width="72">
            <span class="me-2">알림센터</span>
        </h2>
    </div>
    {{--  선생님일 경우에만 보이게  --}}
    <ul class="d-inline-flex gap-2 mb-32 {{ $login_type != 'teacher' ? 'd-none':''}}" >
        <li>
            <button type="button" data-btn-notifi-seq="all" onclick="pushCategorySelect(this)"
                class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover px-32 primary-bg-mian-hover active">전체보기</button>
        </li>
        @if(!empty($notifi))
        @foreach($notifi as $key => $value)
        <li>
            <button type="button" data-btn-notifi-seq="{{ $value->id }}" onclick="pushCategorySelect(this);"
                class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover px-32 primary-bg-mian-hover">
                {{ $value->code_name }}
            </button>
        </li>
        @endforeach
        @endif
    </ul>
    <div data-div-notifi-type-bundle hidden>
        @if(!empty($notifi_type))
        @foreach($notifi_type as $key => $value)
        <input type="hidden" data-input-notifi-type-seq="{{ $value->id }}"
            data-input-notifi-type-code-name="{{ $value->code_name }}">
        @endforeach
        @endif

    </div>
    {{--  parent 일 경우. --}}
    @if($login_type == 'parent')
    <div class="row mt-4 mb-4 pb-2">
        <div class="py-2 text-start pe-3 text-sb-20px">
            총 <span id="table_total_cnt" class="text-primary">0건</span>의 검색 결과가 있습니다.
        </div>
    </div>
    @endif
    <table class="w-100 table-list-style table-h-92">

        <thead>
            <tr>
                <th>구분</th>
                <th hidden>발송</th>
                <th>제목</th>
                <th>작성자</th>
                <th>등록</th>
                <th>-</th>
            </tr>
        </thead>
        <tbody data-push-tby-bundle >
            <tr data-push-tby-row="copy" hidden>
                <input type="hidden" data-push-seq>
                <input type="hidden" data-notifi-seq>
                <input type="hidden" data-notifi-type-seq>
                <input type="hidden" data-move-page>
                <td>
                    <span data-notifi-name
                        class="basic-text-positie"></span>
                </td>
                <td hidden>
                    <span data-notifi-type-name></span>
                </td>
                <td>
                    <span data-push-title
                        class="scale-text-black"></span>
                </td>
                <td>
                    <span class="scale-text-gray_05" data-created-name></span>
                </td>
                <td>
                    <span class="scale-text-gray_05" data-crated-at></span>
                </td>
                <td>
                    <div>
                        {{--  선생님 --}}
                        @if($login_type == 'teacher')
                        <button type="button" onclick="pushMovePage(this)"
                            class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-gray_05 rounded p-3">이동하기</button>
                        @else
                        {{--  학부모 / 학생 --}}
                        <button type="button" onclick="pushSelectDelete(this)"
                            class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-gray_05 rounded p-3">삭제하기</button>
                        @endif
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="d-flex justify-content-between mt-52">
        <div class="w-25">
            <button type="button" onclick="pushRefresh()"
                class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-gray_05 rounded p-3">새로고침</button>
        </div>

        {{-- 페이징  --}}
        <div class="col d-flex justify-content-center">
            <ul class="pagination col-auto" data-ul-push-page="1" hidden>
                <button href="javascript:void(0)" class="btn p-0 prev" data-btn-push-page-prev="1"
                    onclick="pushPageFunc('1', 'prev')">
                    <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                </button>
                <li class="page-item" hidden>
                    <a class="page-link" onclick="">0</a>
                </li>
                <span class="page" data-span-push-page-first="1" hidden
                    onclick="pushPageFunc('1', this.innerText);" disabled>0</span>
                <button href="javascript:void(0)" class="btn p-0 next" data-btn-push-page-next="1"
                    onclick="pushPageFunc('1', 'next')" data-is-next="0">
                    <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                </button>
            </ul>
        </div>

        <div class="w-25 text-end">
            <button type="button" onclick="pushAllDelete();" {{$login_type != 'teacher' ? 'hidden':''}}
                class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-gray_05 rounded p-3">모두
                삭제하기</button>
            <button type="button" onclick="pushAllRead();"
                class="btn-line-xss-secondary text-sb-20px border-dark scale-bg-white text-black rounded p-3 ms-12">전체
                읽음처리</button>
        </div>
    </div>
    {{-- 160px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
    pushSelect(1);
});
// 알림센터 리스트 불러오기.
function pushSelect(page_num){
    const totoal_cnt_el = document.querySelector('#table_total_cnt');
    const notifi_seq = document.querySelector('[data-btn-notifi-seq].active').getAttribute('data-btn-notifi-seq');
    const page = "/teacher/push/select";
    const parameter = {
        notifi_seq: notifi_seq,
        page: page_num,
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 번들 초기화
            const bundle = document.querySelector('[data-push-tby-bundle]');
            const row_copy = bundle.querySelector('[data-push-tby-row="copy"]');
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);
            pushTablePaging(result.pushes, 1);
            totoal_cnt_el.innerText = result.pushes.total + '건';
            result.pushes.data.forEach(function(push){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.setAttribute('data-push-tby-row', 'clone');
                row.querySelector('[data-push-seq]').value = push.id;
                row.querySelector('[data-notifi-seq]').value = push.notifi_seq;
                row.querySelector('[data-notifi-type-seq]').value = push.notifi_type_seq;
                row.querySelector('[data-move-page]').value = push.move_page;

                // 태그에서 이름 가져오기
                const notifi_name = document.querySelector('[data-btn-notifi-seq="'+push.notifi_seq+'"]').innerText;
                row.querySelector('[data-notifi-name]').innerText = notifi_name.trim();
                if(push.is_read == 'Y'){
                    row.querySelector('[data-notifi-name]').classList.remove('basic-text-positie');
                    row.querySelector('[data-notifi-name]').classList.add('scale-text-gray_05');
                }
                const notifi_type_name = document.querySelector('[data-input-notifi-type-seq="'+push.notifi_type_seq+'"]').getAttribute('data-input-notifi-type-code-name');
                row.querySelector('[data-notifi-type-name]').innerText = notifi_type_name;

                row.querySelector('[data-push-title]').innerText = push.push_title;
                row.querySelector('[data-created-name]').innerText = push.created_name;
                row.querySelector('[data-crated-at]').innerText = push.created_at;
                bundle.appendChild(row);
            });

        }else{

        }
    });
}

// 알림센터 카테고리 선택
function pushCategorySelect(vthis){
    if(vthis.classList.contains('active')){
        vthis.classList.remove('active');
    }else{
        // data-btn-notifi-seq 모두 비활성화
        const btn_notifi_seq = document.querySelectorAll('[data-btn-notifi-seq]');
        btn_notifi_seq.forEach(function(item){
            item.classList.remove('active');
        });
        vthis.classList.add('active');
    }
    pushSelect();
}

// 알림센터 페이징 코드
function pushTablePaging(rData, target){
    const from = rData.from;
    const last_page = rData.last_page;
    const per_page = rData.per_page;
    const total = rData.total;
    const to = rData.to;
    const current_page = rData.current_page;
    const data = rData.data;
    //페이징 처리
    const notice_ul_page = document.querySelector(`[data-ul-push-page='${target}']`);
    //prev button, next_button
    const page_prev = notice_ul_page.querySelector(`[data-btn-push-page-prev='${target}']`);
    const page_next = notice_ul_page.querySelector(`[data-btn-push-page-next='${target}']`);
    //페이징 처리를 위해 기존 페이지 삭제
    notice_ul_page.querySelectorAll(".page_num").forEach(element => {
        element.remove();
    });
    //#page_first 클론
    const page_first = document.querySelector(`[data-span-push-page-first='${target}']`);
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
        copy_page_first.removeAttribute("data-span-push-page-first");
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
}
// 페이징 클릭시 펑션
function pushPageFunc(target, type){
    if(type == 'next'){
        const page_next = document.querySelector(`[data-btn-push-page-next="${target}"]`);
        if(page_next.getAttribute("data-is-next") == '0') return;
        // data-ul-push-page 의 마지막 page_num 의 innerText를 가져온다
        const last_page = document.querySelector(`[data-ul-push-page="${target}"] .page_num:last-of-type`).innerText;
        const page = parseInt(last_page) + 1;
        if(target == "1")
            pushSelect(page);
    }
    else if(type == 'prev'){
        // [data-span-push-page-first]  next tag 의 innerText를 가져온다
        const page_first = document.querySelector(`[data-span-push-page-first="${target}"]`);
        const page = page_first.innerText;
        if(page == 1) return;
        const page_num = page*1 -1;
        if(target == "1")
            pushSelect(page);
    }
    else{
        if(target == "1")
            pushSelect(type);
    }
}

// 알림 새로고침
function pushRefresh(){
    //현재 페이지 번호 가져오기.
    const page_num = document.querySelector('[data-ul-push-page="1"] .active').innerText;
    pushSelect(page_num);
}
// 알림 모두 삭제하기.
function pushAllDelete(){
    const notifi_seq = document.querySelector('[data-btn-notifi-seq].active').getAttribute('data-btn-notifi-seq');
    const page = "/teacher/push/all/delete";
    const parameter = {
        notifi_seq: notifi_seq
    };
    const msg =
        `
<div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
<p class="modal-title text-center text-sb-28px mt-28" id="">수신된 모든 알림 메시지를</p>
<p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">삭제하시겠습니까 ?</p>
<p class="modal-title text-center text-sb-20px alert-bottom-m scale-text-gray_05 mt-12" id="">(삭제된 메세지는 복구할 수 없어요.)</p>
</div>
`;
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                const msg =
                    `
<div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
<p class="modal-title text-center text-sb-28px mt-28" id="">모든 알림 메시지가</p>
<p class="modal-title text-center text-sb-28px" id="">삭제되었습니다.</p>
</div>
`;
                sAlert('', msg, 4);
                pushSelect();
            }else{

            }
        });
    });
}

// 알림 모두 읽음처리.
function pushAllRead(){
    const notifi_seq = document.querySelector('[data-btn-notifi-seq].active').getAttribute('data-btn-notifi-seq');

    const page = "/teacher/push/all/read";
    const parameter = {
        notifi_seq: notifi_seq
    };

    const msg =
        `
<div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
<p class="modal-title text-center text-sb-28px mt-28" id="">수신된 모든 알림 메시지를</p>
<p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">읽음 처리하시겠습니까?</p>
<p class="modal-title text-center text-sb-20px alert-bottom-m scale-text-gray_05 mt-12" id="">(읽음 처리 이후에는 되돌릴 수 없습니다.)</p>
</div>
`;
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                const msg =
                    `
<div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
<p class="modal-title text-center text-sb-28px mt-28" id="">모든 알림 메시지가</p>
<p class="modal-title text-center text-sb-28px" id="">읽음 처리되었습니다.</p>
</div>
`;
                sAlert('', msg, 4);
                pushSelect();
            }else{

            }
        });
    });

}
// 선택 삭제하기.
function pushSelectDelete(vthis){
    const tr = vthis.closest('tr');
    const puah_seq = tr.querySelector('[data-push-seq]').value;
    const page = "/teacher/push/all/delete";
    const parameter = {
        push_seq: puah_seq
    };
    const msg =
        `
        <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
        <p class="modal-title text-center text-sb-28px mt-28" id="">선택 알림 메시지를</p>
        <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">삭제하시겠습니까 ?</p>
        <p class="modal-title text-center text-sb-20px alert-bottom-m scale-text-gray_05 mt-12" id="">(삭제된 메세지는 복구할 수 없어요.)</p>
        </div>
        `;
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                const msg =
                    `
                    <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
                    <p class="modal-title text-center text-sb-28px mt-28" id="">선택 알림 메시지가</p>
                    <p class="modal-title text-center text-sb-28px" id="">삭제되었습니다.</p>
                    </div>
                    `;
                sAlert('', msg, 4);
                pushSelect();
            }else{

            }
        });
    });


}
</script>
@endsection
