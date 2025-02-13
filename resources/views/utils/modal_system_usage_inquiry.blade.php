@include('layout.parent_head')

{{-- 모달 / 시스템 사용문의 --}}
<div class="modal fade" id="modal_system_usage" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered" style="min-width:730px">
        <div class="modal-content border-none rounded-4 modal-shadow-style" >
            <div class="modal-header border-0 p-4">
                <h1 class="modal-title fs-5 fw-semibold h-center">
                    <img src="{{ asset('images/robot_icon.svg') }}" width="32">
                    <span class="text-sb-24px ps-1">시스템 사용 문의</span>
                </h1>

                <button type="button" class="btn type_send p-0 modal_close" data-bs-dismiss="modal" aria-label="Close">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"/>
                        <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"/>
                    </svg>
                </button>

            </div>
            <div class="modal-body">
                <div class="row px-4">
                    <div class="col div_send px-2">
                        <div class="mt-3">
                            <!-- <div class="cfs-6 fw-semibold ctext-bc0">자녀를 선택해주세요.</div> -->
                            {{--
                            <div class="cfs-6 my-2">
                                <select class="form-select fs-5 p-3 fw-medium ctext-bc0" id="teach_mess_sel_child" onchange="teachMessChgChildSelect(this)"
                                    style="background-size: 24px 24px;--bs-form-select-bg-img:url(/images/dropdown_arrow_down.svg)">
                                    @if(!empty($students))
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}" data-main-code="{{$student->main_code}}" data-teach-seq="{{$student->teach_seq}}">{{ $student->student_name.'('.$student->grade_name.')' }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            --}}
                            <div class="cfs-6 fw-semibold ctext-bc0">유형을 선택해주세요.</div>
                            <div class="cfs-6 my-2">
                                <select class="form-select fs-5 p-3 fw-medium ctext-bc0 contact_type" id="teach_mess_sel_contact"
                                    style="background-size: 24px 24px;--bs-form-select-bg-img:url(/images/dropdown_arrow_down.svg)">
                                </select>
                            </div>
                            <div class="cfs-6 fw-semibold ctext-bc0">자주 묻는 질문</div>
                            <div class="cfs-6 my-2 border rounded py-4" data-qna>
                                <div class="text-sb-20px row mx-0 jsutify-content-between">
                                    <span class="col">이용권은 얼마인가요?</span>
                                    <div class="col-auto h-center"><img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="24" onclick="modalSystemScriptInfoToggle(this);"></div>
                                </div>
                                <div class="script_info px-3 text-sb-18px scale-text-gray_05 mt-2" hidden>이용권은 ???원 입니다.</div>
                            </div>
                            <div class="cfs-6 my-2 border rounded py-4" data-qna>
                                <div class="text-sb-20px row mx-0 jsutify-content-between">
                                    <span class="col">형제와 같이 사용할 수 있나요?</span>
                                    <div class="col-auto h-center"><img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="24" onclick="modalSystemScriptInfoToggle(this);"></div>
                                </div>
                                <div class="script_info px-3 text-sb-18px scale-text-gray_05 mt-2" hidden>네 충분히 가능합니다.</div>
                            </div>
                            <div class="cfs-6 my-2 border rounded py-4" data-qna>
                                <div class="text-sb-20px row mx-0 jsutify-content-between">
                                    <span class="col">저가형과 고가형의 차이점은 뭔가요?</span>
                                    <div class="col-auto h-center"><img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="24" onclick="modalSystemScriptInfoToggle(this);"></div>
                                </div>
                                <div class="script_info px-3 text-sb-18px scale-text-gray_05 mt-2" hidden>차이가 있습니다.</div>
                            </div>
                        </div>
                        <div>
                            <div class="mt-5">
                                <span class="sel_student_names fs-5 fw-semibold ctext-bc0"></span>
                                <span class="sel_student_names_after fw-semibold ctext-bc0 fs-5 " hidden>에게</span>
                                <span class="cfs-6 fw-semibold ctext-bc0">문의 내용</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div contenteditable="true" class="form-control text_send_message fs-5 p-4" style="min-height: 320px;" placeholder="내용을 입력해주세요."></div>
                        </div>
                        <div class="text-sb-20px text-danger p-3">※ 문의 내용을 검토 후, 영업일 기준 2일 이내 쪽지로 답변드립니다.</div>

                        <div class="row gap-2 p-0 m-0 mt-5">
                            <button type="button" class="col modal_next btn btn-primary-y fs-4 py-3 rounded-3"
                                onclick="teachMessModalSendOneAOne();">
                                <div class="sp_loding spinner-border text-light spinner-border-sm align-middle mb-1 me-2" role="status" hidden></div>
                                쪽지 보내기
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer" hidden>
            </div>
        </div>
    </div>
</div>
<script>

const myModal = new bootstrap.Modal(document.getElementById('modal_system_usage'), {
    keyboard: false
});
myModal.show()

function modalSystemScriptInfoToggle(vthis){
    const qna = vthis.closest('[data-qna]');
    if(qna.querySelector('img').classList.contains('rotate-180')){
        qna.querySelector('img').classList.remove('rotate-180');
        qna.querySelector('.script_info').hidden = true;
    }else{
        qna.querySelector('img').classList.add('rotate-180');
        qna.querySelector('.script_info').hidden = false;
    }
}
</script>
