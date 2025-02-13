
{{-- 모달 / 수정 내역 리스트 --}}
<div class="modal fade" id="modal_edit_history" tabindex="-1" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-shadow-style rounded" style="max-width: 593px;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-b-24px h-center" id="">
                    <img src="{{ asset('images/edit_list_icon.svg') }}" width="32">
                    수정내역 상세
                </h1>
                <button type="button" class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"
                    style="width:32px;height:32px"></button>
            </div>
            <div class="modal-body">
                <div class="overflow-auto tableFixedHead" style="height: auto;">
                    <table class="table">
                        {{-- 굳이 id할 필요 없을듯 modal 안에 .으로 가져오기. --}}
                        <thead class="thd_edit_history">
                        </thead>
                        <tbody class="tby_edit_history">
                            <tr class="copy_tr_edit_history" hidden>
                                <td class="border-bottom-0">
                                    <div>
                                        <p class="text-sb-20px mb-3 title_str">수정 내역 입력</p>
                                        <table class="w-100 table-list-style table-border-xless mb-3">
                                            <colgroup>
                                                <col style="width: 15%;">
                                                <col style="width: 35%;">
                                            </colgroup>
                                            <thead></thead>
                                            <tbody>
                                                <tr class="text-start h-80">
                                                    <td class="text-start ps-4 scale-text-gray_06">
                                                        <p class="text-sb-20px">수정 날짜</p>
                                                    </td>
                                                    <td colspan="3" class="text-start">
                                                        <p class="text-sb-20px ps-4 created_at"></p>
                                                    </td>
                                                </tr>
                                                <tr class="text-start h-80">
                                                    <td class="text-start ps-4 scale-text-gray_06">
                                                        <p class="text-sb-20px">수정 구분</p>
                                                    </td>
                                                    <td colspan="3" class="text-start">
                                                        <p class="text-sb-20px ps-4 scale-text-black log_subject">
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr class="text-start h-80">
                                                    <td class="text-start ps-4 scale-text-gray_06">
                                                        <p class="text-sb-20px">수정 내역</p>
                                                    </td>
                                                    <td colspan="3" class="text-start ">
                                                        <p
                                                            class="text-sb-20px ps-4 align-items-center log_content flex-wrap py-3">
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr class="text-start h-80">
                                                    <td class="text-start ps-4 scale-text-gray_06">
                                                        <p class="text-sb-20px">사유</p>
                                                    </td>
                                                    <td colspan="3" class="text-start">
                                                        <p class="text-sb-20px ps-4 row mx-0 align-items-center log_remark"
                                                            onclick="teachInfoLogRemarkEdit(this);"> </p>
                                                        <div>
                                                            <textarea class="txt_log_remark" cols="30" rows="4" hidden></textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                                <input type="hidden" class="log_seq">
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center none_edit_history mb-3" hidden>
                        <span>수정 내역이 없습니다.</span>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
            <button type="button" onclick="teachInfoLogRemarkUpdate(this)"
                class="btn-lg-primary text-b-24px rounded scale-text-white w-100 justify-content-center">
                <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                저장하기</button>
        </div>
        </div>
    </div>
</div>

<script>
function utilsModalEtHisUserHistoryModal(data) {
    utilsModalEtHistModalEditHistoryClear();
    const user_type = data.user_type;
    const user_key = data.user_key;

    const page = "/manage/log/select";
    const parameter = {
        select_type: user_type,
        select_seq: user_key,
        max_count: 10
    };
    const modal = document.querySelector('#modal_edit_history');

    // 로딩 시작
    queryFetch(page, parameter, function(result) {
        // 로딩 끝
        modal.querySelector('.sp_loding').hidden = true;
        if (result.resultCode == 'success') {
            //초기화
            const thd_edit_history = modal.querySelector('.thd_edit_history');
            const tby_edit_history = modal.querySelector('.tby_edit_history');
            const copy_tr = tby_edit_history.querySelector('.copy_tr_edit_history').cloneNode(true);
            tby_edit_history.innerHTML = '';
            thd_edit_history.innerHTML = '';
            tby_edit_history.appendChild(copy_tr);
            copy_tr.hidden = true;

            // 내역 리스트
            for (let i = 0; i < result.logs.length; i++) {
                const log = result.logs[i];
                const tr = copy_tr.cloneNode(true);
                tr.classList.remove('copy_tr_edit_history');
                tr.classList.add('tr_edit_history');
                tr.hidden = false;

                tr.querySelector('.log_content').innerHTML =
                    (log.log_content||'').trim().replace(/->/gi,
                        '<i class="icon-arrow-right icon-size scale-text-gray_04 mx-2 align-middle"></i>').replace(
                        /\n/gi, '<br>');
                tr.querySelector('.created_at').innerText = (log.created_at || '').replace(/-/gi, '.')
                    .substr(0, 16);
                tr.querySelector('.log_subject').innerText = log.log_subject || '';
                tr.querySelector('.log_remark').innerText = log.log_remark || '사유를 입력해주세요.';
                tr.querySelector('.txt_log_remark').value = log.log_remark || '';
                tr.querySelector('.log_seq').value = log.id;
                tr.querySelector('.title_str').hidden = true;

                if((log.log_remark || '') == ''){
                    tr.querySelector('.title_str').innerText = '수정 내역 입력';
                    thd_edit_history.appendChild(tr);
                }
                else{
                    tr.querySelector('.title_str').innerText = '상세 내역';
                    tr.querySelector('table').classList.add('scale-bg-gray_01');
                    tr.querySelector('.log_remark').removeAttribute('onclick');
                    tby_edit_history.appendChild(tr);
                }
            }
            // thd_edit_history, tby_edit_history 의 첫 tr 각각 .title_str hidden = false;
            thd_edit_history.querySelector('.tr_edit_history .title_str').hidden = false;
            tby_edit_history.querySelector('.tr_edit_history .title_str').hidden = false;


        }
        // tr_edit_history 없으면 내역 없음 표시
        if (modal.querySelectorAll('.tr_edit_history').length == 0) {
            const btm = modal.querySelector('.none_edit_history');
            btm.hidden = false;
        } else {
            const btm = modal.querySelector('.none_edit_history');
            btm.hidden = true;
        }
    });

    //모달 열기
    const myModal = new bootstrap.Modal(document.getElementById('modal_edit_history'), {});
    myModal.show();
}

// 수정내역 모달 비우기.
function utilsModalEtHistModalEditHistoryClear() {
    const modal = document.querySelector('#modal_edit_history');
    const tby_edit_history = modal.querySelector('.tby_edit_history');
    const copy_tr = tby_edit_history.querySelector('.copy_tr_edit_history').cloneNode(true);
    tby_edit_history.innerHTML = '';
    tby_edit_history.appendChild(copy_tr);
    copy_tr.hidden = true;
    //내역 없음 숨김.
    const btm = modal.querySelector('.none_edit_history');
    btm.hidden = false;
}
</script>
