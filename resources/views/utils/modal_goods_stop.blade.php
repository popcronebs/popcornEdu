{{-- 모달 / 이용권 정지 --}}
<div class="modal fade" id="modal_goods_stop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true" role="dialog" >
    <div class="modal-dialog modal-shadow-style rounded" style="max-width: 710px;">
        <div class="modal-content border-none rounded p-3" >
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5 text-b-24px h-center" id="">
                    <img src="{{ asset('images/ticket_icon.svg') }}" width="32">
                    이용권 정지
                </h1>
                <button type="button" style="width:35px;height:35px"
                    class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" data-modal-student-seq />
                <table class="w-100 table-list-style table-border-xless mb-52">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                    </colgroup>
                    <thead></thead>
                    <tbody>
                        <tr class="text-start h-80">
                            <td class="text-start ps-4 scale-text-gray_06">
                                <p class="text-sb-20px">회원 정보</p>
                            </td>
                            <td colspan="3" class="text-start">
                                <p class="text-sb-20px ps-4 scale-text-black">
                                    <span data-modal-student-name></span> /
                                    <span data-modal-region-name></span>
                                    <span data-modal-team-name></span>
                                </p>
                            </td>
                        </tr>
                        <tr class="text-start h-80">
                            <td class="text-start ps-4 scale-text-gray_06">
                                <p class="text-sb-20px">이용권 정보</p>
                            </td>
                            <td colspan="3" class="text-start">
                                <p class="text-sb-20px ps-4">
                                <span data-modal-goods-name></span>
                                <span data-modal-goods-period></span>
                                <span data-modal-goods-type></span>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="mb-52">  </div>
                <table class="w-100 table-list-style table-border-xless lenth_over_hidden">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                    </colgroup>
                    <thead>
                        <tr class="text-start h-80">
                            <td class="text-start ps-4 scale-text-gray_06">
                                <p class="text-sb-20px">정지 횟수</p>
                            </td>
                            <td  class="text-start">
                                <p class="text-sb-20px ps-4 d-flex align-items-center"><b class="scale-text-black me-1" data-modal-stop-cnt>1</b>/ <span data-modal-stop-cnt-max>2</span> 회</p>
                            </td>
                        </tr>
                    </thead>
                    <tbody data-bundle="stop_goods">
                        <tr class="text-start h-80" data-modal-tr-content="1" data-row="copy" hidden>
                            <td rowspan="3"  class="text-start ps-4 scale-text-gray_06">
                                <p class="text-sb-20px">내역</p>
                            </td>
                            <td class="text-start">
                                <p class="text-sb-20px ps-4 d-flex align-items-center">
                                    <span data-modal-log-content="1"></span>
                                    <i class="icon-arrow-right icon-size scale-text-gray_04 mx-2"></i>
                                    <span data-modal-log-content="2"></span>

                                    <b class="studyColor-text-studyComplete ms-1" data-modal-log-content="3"></b>
                                </p>
                            </td>
                        </tr>
                        <tr class="text-start h-80" data-modal-tr-content="2" data-row="copy" hidden>
                            <td class="text-start ps-4">
                                <p class="text-sb-20px" >
                                    <span data-modal-created-at> </span>
                                    <span data-modal-created-name></span>
                                </p>
                            </td>
                        </tr>
                        <tr class="text-start h-80" data-modal-tr-content="3" data-row="copy" hidden>
                            <td class="text-start ps-4">
                                <p class="text-sb-20px" data-modal-log-remark></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- INSERT -->
                <table class="w-100 table-list-style table-border-xless">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                    </colgroup>
                    <thead></thead>
                    <tbody>
                        <tr class="text-start h-80">
                            <td class="text-start ps-4 scale-text-gray_06">
                                <p class="text-sb-20px">정지 기간</p>
                            </td>
                            <td class="text-start ps-4">
                                <div class="d-flex">
                                    <div class="scale-bg-white rounded-3 border-gray d-flex align-items-center">
                                        <button class="btn py-1 " onclick="userlistModalStopDayCnt(this,'down');">
                                            <i class="icon-bash icon-size align-middle"></i>
                                        </button>
                                        <span class="text-m-20px align-text-top">
                                            <b>0</b>일
                                            <input type="number" data-modal-stop-day-cnt onchange="this.previousElementSibling.innerText = this.value"
                                                class="form-control col stop_day_cnt"  value="0" step="15" max="30" min="0" disabled="" hidden>
                                        </span>
                                        <button class="btn py-1" onclick="userlistModalStopDayCnt(this,'up');">
                                            <i class="icon-plus icon-size align-middle"></i>
                                        </button>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <tr class="text-start h-80">
                            <td class="text-start ps-4 scale-text-gray_06">
                                <p class="text-sb-20px">정지 사유</p>
                            </td>
                            <td colspan="3" class="text-start h-100">
                                <input type="text" data-modal-inp-log-remark
                                    class="text-sb-20px ps-4 scale-text-black border-none w-100 h-80" placeholder="정지 사유를 입력해주세요.">
                            </td>
                        </tr>
                        <tr class="text-start h-80">
                            <td class="text-start ps-4 scale-text-gray_06">
                                <p class="text-sb-20px">정지 시작일</p>
                            </td>
                            <td class="text-start h-100 px-4">
                                <input type="date" data-modal-stop-start-date
                                    class="form-control stop_start_date w-auto" value="{{ date('Y-m-d') }}" onchange="userlistStopStartDateChange();">

                                <input type="date" data-modal-stop-end-date hidden
                                    class="form-control stop_end_date w-auto" value="{{ date('Y-m-d') }}" disabled >
                            </td>
                        </tr>
                        <tr class="text-start h-80 scale-bg-gray_01 lenth_over_hidden">
                            <td class="text-start ps-4 scale-text-gray_06">
                                <p class="text-sb-20px">정지 전 유효기간</p>
                            </td>
                            <td colspan="3" class="text-start ps-4">
                                <p class="text-sb-20px">
                                    <span data-modal-goods-start-date
                                        class="goods_start_date"></span>
                                    ~
                                    <span data-modal-goods-end-date
                                        class="goods_end_date"></span>
                                </p>
                            </td>
                        </tr>
                        <tr class="text-start h-80 lenth_over_hidden">
                            <td class="text-start ps-4 scale-text-gray_06">
                                <p class="text-sb-20px">정지 후 유효기간</p>
                            </td>
                            <td colspan="3" class="text-start ps-4">
                                <p class="text-sb-20px">
                                    <span data-modal-after-goods-start-date
                                        class="after_goods_start_date"></span>
                                    ~
                                    <span data-modal-after-goods-end-date
                                        class="after_goods_end_date"></span>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer border-top-0 mt-80">
                <button type="button" onclick="utilsModalStopDayStopModalSave();"
                    class="btn-lg-primary text-b-24px rounded scale-text-white w-100 justify-content-center">정지하기</button>
            </div>
        </div>
    </div>
</div>

<script>
// 이용권 정지 모달 열기.

function utilsModalStopGoodsStopModal(data){
    event.stopPropagation();
    //초기화
    utilsModalStopDayStopModalClear();

    // 체크 회원 없으면 리턴
    if(!data && data.length < 1) {
        toast('선택된 회원이 없습니다.');
        return;
    }

    //학생의 seq를 넣어준다.
    // 체크된 회원 상태에 따라 키 가져오기.
    let student_seqs = '';
    let student_cnt = '';
    // let first_tr = null;
    data.forEach(function(el){
        student_seqs += el.student_seq;
        student_cnt++;
    });

    const student_seq = student_seqs;

    const modal = document.querySelector('#modal_goods_stop');
    modal.querySelector('[data-modal-student-seq]').value = student_seq;
    modal.querySelector('[data-modal-student-name]').innerText = data[0].student_name;
    modal.querySelector('[data-modal-region-name]').innerText = data[0].region_name;
    modal.querySelector('[data-modal-team-name]').innerText = data[0].team_name;

    // stop_cnt_max goods_period 가 12면 2 6이면 1
    const goods_period = data.goods_period;
    const stop_cnt_max = goods_period == 12 ? 2:1;
    modal.querySelector('[data-modal-stop-cnt-max]').innerText = stop_cnt_max;

    //로그 이용권 정지 내역 가져오기.
    //학생의 goods_details 정보 가져오기.
    utilsModalStopDayInfo('stop', function(){
        //모달 열기
        const myModal = new bootstrap.Modal(document.getElementById('modal_goods_stop'), {});
        myModal.show()
    });
}

// 이용권 정지 모달 초기화(클리어)
function utilsModalStopDayStopModalClear(){
    const modal = document.querySelector('#modal_goods_stop');
    modal.querySelector('[data-modal-log-remark]').innerText = '';
    modal.querySelector('[data-modal-student-seq]').innerText = '';
    modal.querySelector('[data-modal-stop-cnt-max]').innerText = 0;
    modal.querySelector('[data-modal-stop-cnt]').innerText = 0;
    modal.querySelectorAll('[data-modal-log-content]').forEach(function(el){
        el.innerText = '';

    })
    modal.querySelector('[data-modal-log-remark]').innerText = '';
    modal.querySelector('[data-modal-created-at]').innerText = '';
    modal.querySelector('[data-modal-created-name]').innerText = '';
    modal.querySelector('[data-modal-goods-start-date]').innerText = '';
    modal.querySelector('[data-modal-goods-end-date]').innerText = '';
    modal.querySelector('[data-modal-after-goods-start-date]').innerText = '';
    modal.querySelector('[data-modal-after-goods-end-date]').innerText = '';
    modal.querySelector('[data-modal-stop-day-cnt]').value = 0;
    modal.querySelector('[data-modal-stop-day-cnt]').onchange();
    modal.querySelectorAll('[data-row="clone"]').forEach(function(el){
        el.remove();
    });
    modal.querySelectorAll('.lenth_over_hidden').forEach(function(el){
        el.hidden = false;
    });
}

//로그 이용권 정지 내역 가져오기.
//학생의 goods_details 정보 가져오기.
function utilsModalStopDayInfo(type, callback){
    const modal = document.querySelector(`#modal_goods_${type}`);
    const student_seq = modal.querySelector('[data-modal-student-seq]').value;

    // 전송
    const page = "/manage/userlist/goods/day/select";
    const parameter = {
        type:type,
        student_seq:student_seq
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            const gd = result.goods_detail;
            const logs = result.logs;
            const title1 = result.title1;
            const title2 = result.title2;

            //input name inp_cb_userinfo checked length - 1
            // const inp_cb_userinfo = document.querySelectorAll('.tr_userinfo input[type=checkbox]:checked');
            // const student_chk_len = inp_cb_userinfo.length - 1;
            // const after_str = student_chk_len > 0 ? ' 외 '+student_chk_len+'명':'의';
            // let title = '';

            if(type == 'stop'){
                modal.querySelector('[data-modal-stop-cnt]').innerText = gd.stop_cnt||0;
                // title = '<span class="fw-bold">'+title1 + '</span> '+after_str+' <span class="fw-bold">' + title2 + '</span> 이용권 정지합니다.';
            }
            // else if(type == 'plus'){
            //     // 내역 tr copy clone~ 초기화
            //     title = '<span class="fw-bold">'+title1 + '</span> '+after_str+' <span class="fw-bold">' + title2 + '</span> 이용권을 연장합니다.';
            // }
            if(logs.length > 0){
                // 내역
                const bundle = modal.querySelector('[data-bundle="stop_goods"]');
                bundle.querySelectorAll('[data-row="clone"]').forEach(function(el){
                    el.remove();
                });
                const row_copy1 = bundle.querySelectorAll('[data-row="copy"]')[0];
                const row_copy2 = bundle.querySelectorAll('[data-row="copy"]')[1];
                const row_copy3 = bundle.querySelectorAll('[data-row="copy"]')[2];

                for(let i = 0; i < logs.length; i++){
                    const tr1 = row_copy1.cloneNode(true);
                    const tr2 = row_copy2.cloneNode(true);
                    const tr3 = row_copy3.cloneNode(true);

                    tr1.setAttribute('data-row', 'clone');
                    tr2.setAttribute('data-row', 'clone');
                    tr3.setAttribute('data-row', 'clone');


                    // log_content = 2023-12-06 ~ 2024-06-19 -> 2023-12-06 ~ 2024-07-04 (15일 정지)
                    //날짜 1 // log_content 에서 ->까지 자름.
                    const log_content = logs[i].log_content;
                    const content1 = log_content.substr(0, log_content.indexOf('->'));
                    //날짜 2
                    const content2 = log_content.substr(log_content.indexOf('->')+2, log_content.indexOf('(')-log_content.indexOf('->')-2);
                    // 몇일 정지
                    const content3 = log_content.substr(log_content.indexOf('(')+1, log_content.indexOf('일')-log_content.indexOf('(')-1);

                    tr1.querySelector('[data-modal-log-content="1"]').innerText = (content1||'').substr(3).replace(/-/g, '.').replace(' ~ ', '-').replace('-20', '-') ;
                    tr1.querySelector('[data-modal-log-content="2"]').innerText = ( content2||'' ).substr(3).replace(/-/g, '.').replace(' ~ ', '-').replace('-20', '-') ;
                    tr1.querySelector('[data-modal-log-content="3"]').innerText =`(${content3}일)`;

                    tr2.querySelector('[data-modal-created-at]').innerText = (logs[i].created_at||'').substr(0,10);
                    tr2.querySelector('[data-modal-created-name]').innerText = logs[i].teach_name;

                    tr3.querySelector('[data-modal-log-remark]').innerText = logs[i].log_remark;

                    tr1.hidden = false;
                    tr2.hidden = false;
                    tr3.hidden = false;

                    bundle.appendChild(tr1);
                    bundle.appendChild(tr2);
                    bundle.appendChild(tr3);
                }
            }
            // if(student_chk_len > 0){
            //     modal.querySelectorAll('.lenth_over_hidden').forEach(function(el){
            //         el.hidden = true;
            //     });
            // }
            // modal.querySelector('.modal-title').innerHTML = title;
            modal.querySelector('[data-modal-goods-start-date]').innerText = gd.start_date;
            modal.querySelector('[data-modal-goods-end-date]').innerText = gd.end_date;
            modal.querySelector('[data-modal-after-goods-start-date]').innerText = gd.start_date;
            modal.querySelector('[data-modal-after-goods-end-date]').innerText = gd.end_date;
            modal.querySelector('[data-modal-goods-name]').innerText = gd.goods_name;
            modal.querySelector('[data-modal-goods-period]').innerText = gd.goods_period;

            let  goods_type = '(분납)';
            if(gd.pay_auto_date||''){
                goods_type = '(일시납)';
            }
            modal.querySelector('[data-modal-goods-type]').innerText = goods_type;
        }
        if(callback != undefined) callback();
    });
}

// 이용권 정지 기간 조정
function userlistModalStopDayCnt(vthis, type){
    const modal = document.querySelector('#modal_goods_stop');
    const stop_day = modal.querySelector('[data-modal-stop-day-cnt]');
    if(type == 'up'){
        stop_day.stepUp();
    }else if(type == 'down'){
        stop_day.stepDown();
    }
    stop_day.onchange();
    userlistModalPlusDayChange(modal, stop_day.value);

    // 이용권 정지 시작일 변경시 종료일 변경
    userlistStopStartDateChange();
}

//
function userlistModalPlusDayChange(modal,day_cnt){
    // if(modal == undefined) modal = document.querySelector('#userlist_modal_goods_day_plus');
    // if(day_cnt == undefined) day_cnt = modal.querySelector('.plus_day_cnt').value || 0;

    // goods_end_date + day_cnt = after_goods_end_date
    const goods_end_date = modal.querySelector('[data-modal-goods-end-date]').innerText;
    const after_goods_end_date = modal.querySelector('[data-modal-after-goods-end-date]');

    const goodsEndDate = new Date(goods_end_date);
    const afterGoodsEndDate = new Date(goodsEndDate.getTime() + day_cnt * 24 * 60 * 60 * 1000);
    after_goods_end_date.innerText = afterGoodsEndDate.toISOString().split('T')[0];
}

// 이용권 정지 시작일 변경시 종료일 변경
function userlistStopStartDateChange(){
    const modal = document.querySelector('#modal_goods_stop');
    const stop_day_cnt = modal.querySelector('[data-modal-stop-day-cnt]').value;
    //stop_start_date + stop_day_cnt = stop_end_date
    const stop_start_date = modal.querySelector('[data-modal-stop-start-date]').value;
    const stop_end_date = modal.querySelector('[data-modal-stop-end-date]');
    const stopStartDate = new Date(stop_start_date);
    const stopEndDate = new Date(stopStartDate.getTime() + stop_day_cnt * 24 * 60 * 60 * 1000);
    stop_end_date.value = stopEndDate.toISOString().split('T')[0];

    //현재일로 부터 과거는 선택 못하게 하기.
    const today = new Date();
    const today_str = today.toISOString().split('T')[0];
    if(stop_start_date < today_str){
        modal.querySelector('[data-modal-stop-start-date]').value = today_str;
        //오늘 + stop_day_cnt = stop_end_date
        modal.querySelector('[data-modal-stop-end-date]').value = new Date(today.getTime() + stop_day_cnt * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
    }
}

// 이용권 정지 저장
function utilsModalStopDayStopModalSave(){
    const modal = document.querySelector('#modal_goods_stop');
    const student_seq = modal.querySelector('[data-modal-student-seq]').value;
    const stop_day_cnt = modal.querySelector('[data-modal-stop-day-cnt]').value;
    const log_remark = modal.querySelector('[data-modal-inp-log-remark]').value;
    const stop_start_date = modal.querySelector('[data-modal-stop-start-date]').value;
    const stop_end_date = modal.querySelector('[data-modal-stop-end-date]').value;

    // cnt가 0이면 리턴
    if(stop_day_cnt == 0){
        sAlert('', '정지일수를 입력해주세요.',1,function(){
            modal.querySelector('[data-modal-stop-day-cnt]').focus();
        });
        return;
    }

    // 신청사유 입력 안되어 있으면 리턴
    if(log_remark == ''){
        sAlert('', '신청사유를 입력해주세요.',1,function(){
            modal.querySelector('[data-modal-inp-log-remark]').focus();
        });
        return;
    }

    const page = "/manage/userlist/goods/day/stop/insert";
    const parameter = {
        student_seqs:student_seq,
        stop_day_cnt:stop_day_cnt,
        log_remark:log_remark,
        stop_start_date:stop_start_date,
        stop_end_date:stop_end_date
    };

    // 저장 확인 메세지
    const msg = '이용권을 정지하시겠습니까?';
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if(result.resultCode == 'success'){
                if((result.cant_students||'') == '')
                sAlert('', '저장되었습니다.', 4, function(){
                    window.location.reload();
                });
                else{
                    const names = result.cant_students.substr(0, result.cant_students.length - 1);
                    sAlert('', '횟수 초과로 다음 학생('+names+')은 더 이상 정지할 수 없습니다.', 4, function(){
                        window.location.reload();
                    });
                }
                //모달 닫기
                modal.querySelector('.btn-close').click();

            }else{
                sAlert('', '저장에 실패하였습니다.');
            }
        });
    });
}

</script>
