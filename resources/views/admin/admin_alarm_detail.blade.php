<style>
    #alarm_v_pills_tab_content .tab-pane {
        display: none !important;
    }

    #alarm_v_pills_tab_content .tab-pane.show {
        display: block !important;
    }
    .tr_save_str.active{
        background: #f9f9f9;
    }
</style>

{{-- 모달 / 문자 보내기  --}}
<div class="modal fade " id="alarm_modal_div_member_select" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog rounded modal-xl" style="max-width: 1426px;">
      <div class="modal-content border-none rounded modal-shadow-style p-0 overflow-hidden">
        <div class="modal-header border-bottom-0 d-none">
        </div>
        <div class="modal-body p-0">
          <div class="d-flex half-modal">
            <div class="half-left">
              <div class="half-left-content-title px-4 pt-4 mb-52">
                <p class="modal-title h-center fs-5 text-b-24px mb-3" id="">
                    <img src="{{ asset('images/sms_icon.svg') }}" width="32">
                    학생 선택하기</p>
              </div>
              <div class="half-date ">
                <div class="px-4">
                  <div class="d-flex">
                    <div class="d-inline-block select-wrap select-icon h-62 pe-6">
                      <select class="border-gray lg-select text-sb-20px h-62" id="alarm_sel_sch_type" onchange="alarmMemSelectType(this);">
                        <option selected="" value="name">이름</option>
                        <option value="id">아이디</option>
                        <option value="phone">휴대폰번호</option>
                        <option value="school">학교</option>
                        {{-- <option value="grade">학년</option> --}}
                        <option value="ticket">이용권</option>
                        <option value="parent">학부모</option>
                        <option value="teacher">담당선생님</option>
                      </select>
                        <select id="alarm_sel_sch_grade" class="form-select" hidden>
                            <option value="e1">초1</option>
                            <option value="e2">초2</option>
                            <option value="e3">초3</option>
                            <option value="e4">초4</option>
                            <option value="e5">초5</option>
                            <option value="e6">초6</option>
                            <option value="m1">중1</option>
                            <option value="m2">중2</option>
                            <option value="m3">중3</option>
                            <option value="h1">고1</option>
                            <option value="h2">고2</option>
                            <option value="h3">고3</option>
                        </select>
                    </div>
                    <label class="label-search-wrap ps-6 w-100">
                      <input id="alarm_inp_sch_text" type="text" onkeyup="if(event.keyCode==13){ alarmSelectUser(); }"
                      class="lg-search border-gray rounded text-m-20px w-100" placeholder="학생 이름을 검색해주세요.">
                    </label>
                  </div>
                  <div class="d-none justify-content-between align-items-center mt-52">
                    <div class="d-none">
                      <div class="d-inline-block select-wrap select-icon scale-bg-white h-52 me-12">
                        <select class="border-gray lg-select text-sb-20px h-52 py-1 rounded"
                        onchange="alarmSelectDateType(this,'[data-inp-sms-send-start-date2]', '[data-inp-sms-send-end-date2]')">
                          <option value="">기간 설정</option>
                          <option value="0">지난1주일</option>
                        </select>
                      </div>

                      <div class="border-gray rounded-3 h-52 px-32 h-center">
                            <input data-inp-sms-send-start-date2 type="date" class="border-0 text-m-20px gray-color" value="{{ date('Y-m-d') }}">
                            ~
                            <input data-inp-sms-send-end-date2 type="date" class="border-0 text-m-20px gray-color" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="d-flex">
                      <button hidden type="button" class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-4 h-52">선택 학생 추가</button>
                    </div>
                  </div>
                  <div class="table-box-wrap" >
                    <div class="d-flex mt-32 table-x-scroll table-scroll w-100 pb-4" data-student-sel-div-select
                    style="max-height: calc(100vh - 430px);max-width:880px!important;">
                      <table class="table-style w-100" style="min-width: 100%;">
                        <thead class="">
                          <tr class="text-sb-20px modal-shadow-style rounded">
                            <th style="width: 80px">-</th>
                            <th>학교/학년</th>
                            <th>이름/아이디</th>
                            <th>학생 휴대전화</th>
                            <th>학부모</th>
                            <th>학부모 휴대전화</th>
                          </tr>
                        </thead>
                        <tbody class="tby_student">
                          <tr class="copy_tr_student text-m-20px">
                            <input type="hidden" class="student_seq">
                            <input type="hidden" class="parent_seq">
                            <input type="hidden" class="push_key">
                            <td class=" py-2" onclick="event.stopPropagation();this.querySelector('input').click();">
                                <label class="checkbox mt-1">
                                    <input type="checkbox" class="chk" onchange="alarmSelMemberAdd(this);"
                                        onclick="event.stopPropagation();">
                                    <span class="" onclick="event.stopPropagation();">
                                    </span>
                                </label>
                            </td>
                            <td hidden class="group_type">
                            </td>
                            <td class=" py-2">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                                <span class="gray-color school_name" data="#학교"></span>
                                <p class="gray-color grade" data="#학년"></p>
                            </td>
                            <td class=" py-2 ">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                                <span class="gray-color student_name" data="#학생이름"></span>
                                <p class="gray-color student_id" data="#학생아이디"></p>
                            </td>
                            <td class=" py-2 student_phone" data="#학생 휴대전화">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                            </td>
                            <td class=" py-2">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                                <span class="gray-color parent_name" data="#학부모 이름"></span>
                                <p class="gray-color parent_id" data="#학부모아이디"></p>
                            </td>
                            <td class=" py-2 parent_phone" data="#학부모 휴대전화">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                            </td>
                            <td hidden class="teach_name"></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div hidden class="text-b-28px text-center mt-5" data-student-all-div-select>
                        전체 학생이 선택되었습니다.
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="sticker" class="half-right px-32 overflow-hidden rounded-end-3" style="top: 0px; position: relative; max-width: 502px;">
              <div class="half-right-content-title mt-4 mb-52">
                <button type="button" onclick="alarmModalCloseChkSelectMember();"
                class="btn close-btn p-0 h-center" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('images/black_x_icon.svg') }}" width="32">
                </button>
              </div>
              <div class="half-wrap">
                <div class="d-inline-block select-wrap select-icon mb-52">
                    <select data-select-send-sel-post-type onchange="alarmSendChgSelectType(this);"
                    class="rounded-pill border-0 sm-select text-sb-24px py-0 ps-0 pe-5" style="outline:none">
                        <option value="sel">선택 학생</option>
                        {{-- 전체 학생 --}}
                        <option value="all">전체 학생</option>
                    </select>
                  </div>
                <div style="min-height: 570px;">
                  <p class="text-sb-20px mb-4">유형을 선택해주세요.</p>
                  <div class="d-flex gap-3 mb-4">
                    <label class="radio d-flex align-items-center">
                      <input type="radio" data-type="kakao" name="send_type">
                      <span class=""></span>
                      <p class="text-m-24px ms-2">알림톡</p>
                    </label>
                    <label class="radio d-flex align-items-center">
                      <input type="radio" data-type="sms" name="send_type">
                      <span class=""></span>
                      <p class="text-m-24px ms-2">SMS</p>
                    </label>
                    <label class="radio d-flex align-items-center">
                      <input type="radio" data-type="push" name="send_type">
                      <span class=""></span>
                      <p class="text-m-24px ms-2">PUSH</p>
                    </label>
                  </div>
                  <p class="text-sb-20px mb-4">대상을 선택해주세요.</p>
                  <div class="d-flex gap-3">
                    <label class="radio d-flex align-items-center">
                      <input type="radio" data-target="student" name="send_target">
                      <span class=""></span>
                      <p class="text-m-24px ms-2">학생만</p>
                    </label>
                    <label class="radio d-flex align-items-center">
                      <input type="radio" data-target="parent" name="send_target">
                      <span class=""></span>
                      <p class="text-m-24px ms-2">학부모만</p>
                    </label>
                    <label class="radio d-flex align-items-center">
                      <input type="radio" data-target="all" name="send_target">
                      <span class=""></span>
                      <p class="text-m-24px ms-2">학부모 포함</p>
                    </label>
                  </div>
                  <div class="py-4 px-12 div-shadow-style rounded-3 mt-52 mb-4 scale-bg-white">
                    <div class="d-flex justify-content-between align-items-center px-12">
                      <p class="text-sb-24px">선택 학생 목록</p>
                    <button class="btn p-0 m-0 h-center active border-0" onclick="alarmSelStToggleList(this);">
                        <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                    </button>
                    </div>
                    <ul data-select-student-list-bundle2  class="row row-gap-1 pt-4 px-2 overflow-auto" style="max-height: 233px;">
                        <li data-select-student-list-row2="clone" class="col-6 p-1"  hidden>
                            <div class="d-flex justify-content-between align-items-center scale-bg-gray_01 px-2 py-2 rounded-pill">
                              <span class="text-m-20px scale-text-gray_05" data-student-name=""></span>
                              <button type="button" class="btn p-0 h-center" onclick="alarmSelMemberDel(this)">
                                <img src="https://sdang.acaunion.com/images/gray_x_icon.svg" width="24">
                            </button>
                            </div>
                          </li>
                    </ul>
                  </div>
                </div>



              </div>

            <div class="pb-4">
              <button type="button" onclick="alarmModalNextStep();"
              class="btn-lg-primary justify-content-center text-b-24px rounded-3 scale-text-white w-100">다음</button>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top-0 py-0 px-3 pb-2 mt-52 d-none"></div>
      </div>
    </div>
  </div>
</div>
{{-- 모달 / 문자보내기 끝 --}}
{{-- 모달 / 문자 전송 폼 --}}
<div class="modal fade" id="alarm_modal_div_send" tabindex="-1" aria-labelledby="alarm_div_sendLabel" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog rounded modal-lg">
    <div class="modal-content border-none rounded p-3 modal-shadow-style">
      <div class="modal-header border-bottom-0">
        <h1 class="modal-title fs-5 text-b-24px h-center" id="">
          <img src="{{ asset('images/sms_icon.svg') }}" width="32">
          <span data-span-alarm-post-type >선택 학생</span>
          {{-- <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.45166 9.74756L11.2186 13.5431C11.6061 13.9335 12.2367 13.9359 12.6272 13.5484L16.4565 9.74756" stroke="#DCDCDC" stroke-width="2.5" stroke-miterlimit="10" stroke-linecap="round"/>
          </svg> --}}

        </h1>
        <button type="button" class="btn close-btn p-0 h-center" data-bs-dismiss="modal" aria-label="Close">
            <img src="{{ asset('images/black_x_icon.svg') }}" width="32">
        </button>

      </div>
      <div class="modal-body">

        <button type="button" onclick="alarmModalStep('prev');"
        class="btn-ms-secondary justify-content-between px-4 text-sb-20px rounded-3 scale-bg-white div-shadow-style scale-text-black w-100 mb-52">
          <span>학생목록 추가 및 삭제</span>
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9.74756 16.5479L13.5431 12.7809C13.9335 12.3934 13.9359 11.7628 13.5484 11.3723L9.74756 7.54297" stroke="#222222" stroke-width="2.5" stroke-miterlimit="10" stroke-linecap="round"/>
          </svg>

        </button>

        <div class="row w-100">
          <div class="col-6 mb-32 ps-0 pe-2">
            <label class="label-input-wrap w-100">
              <input type="text" data-input-alarm-send-type
              class="smart-ht-search border-gray rounded text-m-20px w-100" placeholder="알림톡" disabled>
            </label>
          </div>
          <div class="col-6 ps-2 pe-0 mb-32">
            <label class="label-input-wrap w-100">
              <input type="text" data-input-alarm-send-target
              class="smart-ht-search border-gray rounded text-m-20px w-100" placeholder="학생만" disabled>
            </label>
          </div>
      </div>
      <p class="text-sb-20px mb-3">전송 내용</p>
      <div class="w-100">

        <label class="label-input-wrap w-100 mb-2 row gap-2">
          <input type="text" class="mform_title smart-ht-search border-gray rounded text-m-20px w-100 col" placeholder="제목을 입력해주세요.">
          <input type="text" class="kko_code smart-ht-search border-gray rounded text-m-20px w-100 col" placeholder="알림톡 코드" hidden>
        </label>

        <textarea name="" id="" class="mform_content border-gray rounded text-r-20px w-100 textarea-resize-none mb-2 p-4" cols="30" rows="10" placeholder="내용을 입력해주세요"></textarea>
        <label class="label-input-wrap w-100 mb-2">
          <input type="text" data-input-url-str
            class="url smart-ht-search border-gray rounded text-m-20px w-100" placeholder="URL을 입력해주세요. (선택사항)">
        </label>
        <div class="filebox mb-2">
          <input data-input-file-upload-img-str
          class="smart-ht-search border-gray rounded text-m-20px w-100 upload-name scale-text-gray_05" value="이미지를 첨부해주세요." placeholder="이미지를 첨부해주세요">
          <label for="file">
            <span class="px-3" onclick="document.querySelector('#alarm_inp_imgfile').click();">이미지 첨부하기</span>
          </label>
          <input type="file" id="alarm_inp_imgfile" accept="image/*" onchange="alarmImgFileChange(this);">

          <img id="alarm_img_file" src="" alt="" style="height:150px" onclick="imgClear(this)" hidden>
          {{-- <button class="btn btn-sm btn-outline-secondary" >이미지 첨부</button> --}}
        </div>
        <div class="rounded d-flex">
          <div class="d-flex scale-bg-gray_01 px-4 align-items-center rounded-start">
            <p class="text-sb-20px white-space scale-text-gray_05">발신번호</p>
          </div>
          <label class="label-input-wrap flex-fill">
            <input type="text" class="smart-ht-search rounded-end scale-bg-gray_01 border-none text-m-20px w-100 text-center" placeholder="010-1234-1234">
          </label>
        </div>
        <div class="d-flex justify-content-between mt-32">
          <div class="">
            <button type="button" onclick="alarmSaveStrOpen();"
            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-4">목록에서 내용 가져오기</button>
          </div>
          <div>
            <button type="button" onclick="alarmMessageFormClear();"
            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-4">새로고침</button>
            <button type="button" onclick="alarmMessageFormSave();"
            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-4">목록에 저장</button>
          </div>
        </div>
        <label class="checkbox d-flex align-items-center mt-52 mb-12">
          <input type="checkbox" class="" id="alarm_chk_reserv" onchange="alarmReservCheck(this);">
          <span class=""></span>
          <p class="text-sb-20px ms-2">발송예약하기</p>
        </label>
        <label class="label-input-wrap w-100 mb-2">
          <input type="text" data-input-alarm-reserv="0"
          class="smart-ht-search border-gray rounded text-m-20px w-100" placeholder="선택안함">
          <div data-input-alarm-reserv="1"
          class="h-center smart-ht-search border-gray rounded w-100" hidden>
              <input id="alarm_rev_date" class="text-m-20px border-0" type="date" value="{{ now()->format('Y-m-d') }}">
              <input id="alarm_rev_time" class="text-m-20px border-0" type="time" value="{{ now()->format('H:i:00') }}">
          </div>
        </label>
      </div>
      <div class="modal-footer border-top-0 p-0 pb-2 mt-52">
        <div class="row w-100 ">
          <div class="col-6 ps-0 pe-6">
            <button type="button" onclick="alarmModalStep('prev');"
            class="btn-lg-secondary text-sb-24px rounded scale-bg-gray_01 scale-text-gray_05 w-100 justify-content-center">이전</button>
          </div>
          <div class="col-6 ps-6 pe-0">
            <button type="button" onclick="alarmSendMsg();"
            class="btn-lg-primary text-sb-24px rounded scale-text-white w-100 text-center justify-content-center">문자 전송하기</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
{{-- 모달 / 문자 전송 폼 끝  --}}
{{-- 모달 / 목록에서 내용 가져오기. --}}
<div class="modal fade" id="alarm_modal_div_save_str_list" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog rounded modal-xl" style="max-width: 1426px;">
      <div class="modal-content border-none rounded modal-shadow-style p-0 overflow-hidden">
        <div class="modal-header border-bottom-0 d-none">
        </div>
        <div class="modal-body p-0">
          <div class="d-flex half-modal">
            <div class="half-left">
              <div class="half-left-content-title px-4 pt-4 mb-52 h-center">
                <img src="{{ asset('images/yellow_upload_icon.svg') }}" width="32">
                <p class="modal-title fs-5 text-b-24px " id="">목록에서 내용 가져오기</p>
              </div>
              <div class="half-date ">
                <div class="px-4">
                  <div class="d-flex justify-content-between align-items-center mt-52">
                    <div class="">
                      <div class="d-inline-block select-wrap select-icon scale-bg-white h-52 me-12">
                        <select data-select-alarm-save-list2 onchange="alarmSaveChgSelectType(this);"
                        class="border-gray lg-select text-sb-20px h-52 py-1 rounded">
                            <option value="sms">SMS</option>
                            <option value="kakao">알림톡</option>
                            <option value="push">PUSH</option>
                        </select>
                      </div>

                    </div>
                    <div class="d-flex">
                      <button type="button" onclick="alarmSaveStrDelete();"
                      class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-4 h-52">선택 문구 삭제</button>
                    </div>
                  </div>
                  <div class="table-box-wrap">
                    <div class="d-flex mt-32 table-x-scroll table-scroll w-100 pb-4">
                      <table class="table-style w-100" style="min-width: 100%;">
                        <thead class="">
                          <tr class="text-sb-20px modal-shadow-style rounded">
                            <th style="width: 80px">-</th>
                            <th>분류</th>
                            <th>제목</th>
                            <th hidden class="kko_column">알림톡 코드</th>
                            <th>내용</th>
                          </tr>
                        </thead>
                        <tbody id="alarm_tby_save_str2">
                            <tr class="copy_tr_save_str text-m-20px h-92" hidden="" onclick="alarmTrSaveStr(this)">
                                <input type="hidden" class="mform_seq">
                                <input type="hidden" class="img_data">
                                <input type="hidden" class="img_size">
                                <input type="hidden" class="loding_place">
                                <input type="hidden" class="url" >
                                <td class=" py-2">
                                    <label class="checkbox mt-1">
                                        <input type="checkbox" class="chk">
                                        <span class="">
                                        </span>
                                    </label>
                                </td>
                                <td class=" py-2">
                                    <p class="mform_type">SNS</p>
                                </td>
                                <td class="mform_title py-2">
                                    제목이 들어갈 영역입니다.
                                </td>
                                <td hidden class="py-2 kko_column">
                                    <p class="kko_code">#KKOCODE</p>
                                </td>
                                <td class="py-2">
                                    <p class="mform_content gray-color"></p>
                                    <img class="preview" src="" alt="" hidden="">
                                </td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div>

            </div>
            <div id="sticker" class="half-right px-32 overflow-hidden rounded-end-3" style="top: 0px; position: relative; max-width: 502px;">
              <div class="half-right-content-title mt-4 mb-52">
                <button type="button" class="btn close-btn p-0" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('images/black_x_icon.svg') }}" width="32">
                </button>
              </div>
              <div class="half-wrap ">

                <p class="text-sb-24px mb-52">선택 문자 수정 및 미리보기</p>
                <div style="min-height: 570px;">
                  <label class="label-input-wrap w-100 mb-2 row gap-2">
                    <input type="text" data-input-alarm-save-str-title
                    class="smart-ht-search border-gray rounded text-m-20px w-100 col" placeholder="제목을 입력해주세요.">
                    <input type="text" data-input-alarm-save-kko-code
                    class="smart-ht-search border-gray rounded text-m-20px w-100 col" placeholder="알림톡 코드" hidden>
                  </label>
                  <textarea data-input-alarm-save-str-content
                  class="border-gray rounded text-r-20px w-100 textarea-resize-none mb-2 p-4" cols="30" rows="10" placeholder="내용을 입력해주세요"></textarea>

                    <input type="hidden" data-input-alarm-save-url-str>
                </div>
              </div>

            <div class="pb-4">
              <button type="button" onclick="alarmSaveStrGetDetail();"
              class="btn-lg-primary justify-content-center text-b-24px rounded-3 scale-text-white w-100">적용하기</button>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top-0 py-0 px-3 pb-2 mt-52 d-none"></div>
      </div>
    </div>
  </div>
</div>
{{-- 모달 /목록에서 끝 --}}

<div class="col-12 pe-3 ps-3 position-relative" hidden>
    {{-- 학년 분류 --}}
    <div id="alarm_div_hidden_codes" hidden>
        <select id="alarm_sel_grade_codes">
        @if(!empty($grade_codes))
            @foreach ($grade_codes as $code)
                <option value="{{ $code['id'] }}" grade="{{ $code['code_name'] }}">{{ $code['code_name'] }}</option>
            @endforeach
        @endif
        </select>
    </div>
    {{-- 탭 4개 = 문자 / 알림 보내기 / 저장 문구 목록 / 최근 발송 내역 / 예약 목록 - 아래 모양과 다르게 --}}
    <div class="nav nav-pills mt-2 mb-2 justify-content-center" id="alarm_v_main_tab" role="tablist"
        aria-orientation="vertical">
        <button class="nav-link active" id="alarm_v_main_tab_1" data-bs-toggle="pill"
            data-bs-target="#alarm_v_main_layout_1" type="button" role="tab" aria-controls="alarm_v_main_layout_1"
            aria-selected="true">문자 / 알림 보내기</button>
        <button class="nav-link" id="alarm_v_main_tab_2" data-bs-toggle="pill" data-bs-target="#alarm_v_main_layout_2"
            type="button" role="tab" aria-controls="alarm_v_main_layout_2" aria-selected="false"
            onclick="alarmSaveStrSelect();">저장 문구 목록</button>
        <button class="nav-link" id="alarm_v_main_tab_3" data-bs-toggle="pill" data-bs-target="#alarm_v_main_layout_3"
            type="button" role="tab" aria-controls="alarm_v_main_layout_3" aria-selected="false"
            onclick="alarmLastSendSelect()">최근 발송 내역</button>
        <button class="nav-link" id="alarm_v_main_tab_4" data-bs-toggle="pill" data-bs-target="#alarm_v_main_layout_4"
            type="button" role="tab" aria-controls="alarm_v_main_layout_4" aria-selected="false"
            onclick="alarmReservSelect();">예약 목록</button>
    </div>

    <div class="tab-content row justify-content-center" id="alarm_v_pills_tab_content">


        {{-- 문자 / 알림 보내기 --}}
        <div class="tab-pane fade col-12 show active" id="alarm_v_main_layout_1" role="tabpanel"
            aria-labelledby="alarm_v_main_tab_1" tabindex="0">
            {{-- 알림 전송 폼 --}}
            <div id="alarm_div_send" class="row p-5">
                <div class="d-flex gap-5">
                    {{-- select 전체회원  --}}
                    <select class="form-select mt-3 mb-3 col-1" style="width:250px;"
                        onchange="alarmSelectMember(this);">
                        <option value="" selected>전체회원</option>
                        <option value="sel_member">선택회원</option>
                        {{-- 이용권 만료 한달전 ~ 만료전 --}}
                        <option value="coming_expire">만료임박회원</option>
                        <option value="expire_member">만료회원</option>
                    </select>
                    {{-- 선택 이름 5~7개 이하로  --}}
                    <div id="alarm_div_sel_member" class="d-flex align-items-center gap-2">
                        <span class="copy_sp_sel_member" hidden>#이름(#학년)</span>
                    </div>
                </div>



                {{-- 선택회원 / 회원 가져오기 / 선택회원 일때만 --}}
                <div class="p-0 pb-4">
                    <button class="btn_get_member btn btn-outline-secondary" onclick="alarmGetMember();"
                        style="width:250px" hidden>회원 선택 하기</button>
                </div>

                {{-- 좌우 끝 정렬 --}}
                <div class="d-flex justify-content-between align-items-center ps-4 mb-3">
                    <span>
                        <span id="alarm_sp_type">알림톡</span>
                        보내기
                    </span>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <input type="text" class="mform_title w-100 border-0" placeholder="제목">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <textarea cols="30" rows="5" class="mform_content w-100 border-0" placeholder="내용"
                                onkeyup="alarmContentByteSize(this)"></textarea>
                            <div class="text-secondary w-100 text-end" style="padding:5px;margin:10px 0px 0px 0px;"
                                id="alarm_div_sb_byte" hidden>
                                <span id="alarm_sp_mtype">단문</span>
                                <span id="alarm_sp_bite">0/90</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col">
                                    <div class="text-secondary" ">권장용량 300KB</div>
                                                        <div id="alarm_div_imgsize" class="text-secondary"></div>
                                                        </div>
                                                        <div class="col position-relative" style="min-height:100px;">
                                                            <div class="position-absolute bottom-0 end-0 pe-3">
                                                                <button class="btn btn-sm btn-outline-secondary px-4" onclick="alarmMessageFormClear();">새로고침</button>
                                                                <button class="btn btn-sm btn-outline-secondary px-3" onclick="alarmMessageFormSave()">목록에저장</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="form-check">
                                        <label class="form-check-label" for="alarm_chk_reserv">
                                            발송예약
                                        </label>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>

                                        </div>
                                    </div>
                                </div>

                                {{-- 회원 가져오기 DIV --}}
                                <div id="alarm_div_member_select" class="position-absolute bg-white top-0 start-0 bottom-0 end-50 p-4 border border-secondary"
                                style="width:70%;margin:auto;height:80vh" hidden>
                                    {{-- 회원검색 --}}
                                    <table class="table table-bordered text-center align-middle">
                                        <tr>
                                            <td class="table-light" style="width: 80px;">회원 검색</td>
                                            <td class="col">
                                                <div class="d-flex gap-2">
                                                </div>
                                            </td>
                                            <td class="table-light" style="width:80px;">
                                                소속
                                            </td>
                                            <td class="col border-0">
                                    <div class="d-flex gap-2">
                                        <select id="alarm_sel_region" class="form-select d-inline-block" onchange="alarmSelectRegion(this);">
                                            <option value="">소속전체</option>
                                                    @if (!empty($regions))
                                                @foreach ($regions as $reg)
                                                    <option value="{{ $reg['id'] }}" area="{{ $reg['area'] }}">
                                                        {{ $reg['region_name'] }}</option>
                                                @endforeach
                                                @endif
                                        </select>
                                        <select id="alarm_sel_team" class="form-select d-inline-block team">
                                            <option value="">팀전체</option>
                                            @if (!empty($team))
                                                @foreach ($team as $t)
                                                    <option value="{{ $t['team_code'] }}"
                                                        region="{{ $t['region_seq'] }}" hidden>{{ $t['team_name'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                        </td>
                        <td class="border-start-0">
                            <button id="alarm_btn_sch" class="btn btn-outline-secondary px-4"
                                onclick="alarmSelectUser()" style="width:100px">
                                <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true"
                                    hidden></span>
                                검색
                            </button>
                        </td>
                    </tr>
                </table>

                {{-- select / 선택회원 / 만료임박회원 / 만료회원   --}}
                <div class="row p-0 m-0 align-items-center mb-3">
                    <select class="sel_user_type form-select col-auto" style="width:250px;">
                        <option value="" selected>선택회원</option>
                        <option value="coming_expire">만료임박회원</option>
                        <option value="expire_member">만료회원</option>
                    </select>
                    <div class="btn-group col-auto">
                        {{-- 학생/학부모 --}}
                        <input type="radio" name="alarm_sel_user_type" value="student" class="btn-check"
                        id="alarm_radio_student" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="alarm_radio_student">학생</label>

                        <input type="radio" name="alarm_sel_user_type" value="parent" class="btn-check"
                        id="alarm_radio_parent" >
                        <label class="btn btn-outline-secondary btn-sm" for="alarm_radio_parent" >학부모</label>
                    </div>
                    <div class="col text-end">
                        <span>선택한 회원</span>
                        <span id="alarm_sp_sel_user_cnt">0</span>
                        <span>명</span>
                    </div>
                </div>

                {{-- table / checkbox / 구분 / 회원명|아이디 / 지역 / 학교 / 학년 / 이용권 / 이용권 기간 / 학부모 / 학부모 휴대번호 / 담당 선생님  --}}
                <div class="overflow-auto tableFixedHead" style="height:calc(100% - 150px)">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light align-middle">
                            <tr>
                                <th onclick="event.stopPropagation();this.querySelector('input').click();">
                                    <input type="checkbox" onchange="alarmSelectUserAllChkbox(this);" onclick="event.stopPropagation();">
                                </th>
                                <th>구분</th>
                                <th>회원명 / 아이디</th>
                                <th>휴대폰 번호</th>
                                <th>지역</th>
                                <th>학교</th>
                                <th>학년</th>
                                <th>이용권</th>
                                <th>이용권 기간</th>
                                <th>학부모</th>
                                <th>학부모<br>휴대번호</th>
                                <th>담당선생님</th>
                            </tr>
                        </thead>
                        <tbody class="tby_student">
                            <tr class="copy_tr_student" hidden>
                                <td onclick="event.stopPropagation();this.querySelector('input').click();">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                    <input class="chk" type="checkbox" hidden onchange="alarmSelectUserChkbox();" onclick="event.stopPropagation();">
                                </td>
                                <td class="group_type" data="#구분">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                </td>
                                <td data="#회원명/아이디">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                    <div>
                                        <div class="student_name"></div>
                                        <div class="student_id"></div>
                                    </div>
                                </td>
                                <td class="phone" data="#휴대폰번호">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                    <div class="student_phone"></div>
                                </td>
                                <td class="" data="#지역">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                </td>
                                <td class="school_name" data="#학교">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                </td>
                                <td class="grade" data="#학년">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                </td>
                                <td class="ticket" data="#이용권">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                </td>
                                <td class="" data="#이용권기간">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                </td>
                                <td data="#학부모">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                    <div>
                                        <div class="parent_name"></div>
                                        <div class="parent_id"></div>
                                    </div>
                                </td>
                                <td class="parent_phone" data="#학부모휴대번호">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                </td>
                                <td class="teach_name" data="#담당선생님">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                </td>
                                <input type="hidden" class="push_key">
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-end pt-2">
                    <button class="btn btn-sm btn-outline-secondary me-4 px-4"
                        onclick="alarmSelectUserClose();">닫기</button>
                    <button class="btn btn-sm btn-outline-primary px-3" onclick="alarmSelectUserAdd();">선택 회원 추가</button>
                </div>
            </div>

            {{-- 리스트 --}}
            <div id="alarm_div_select_member" hidden class="col-3 position-absolute border bg-white border-secondary"
                style="top: 50%; left: 50%; transform: translate(-50%, -50%);width:300px;">
                <h5 class="member_cnt text-center mt-2 text-primary"></h5>
                <div class="select_member_list list-group list-group-flush border-bottom scrollarea"
                    style="height:calc(50vh);overflow:auto;">
                    <a href="javascript:void(0)" class="copy_member_list list-group-item list-group-item-action py-3 lh-sm"
                    onclick="alarmSelectMemberDelete(this);" hidden>
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <strong class="member_name mb-1">#이름(#학년)</strong>
                            <small class="member_type text-body-secondary">학생</small>
                        </div>
                        <div class="member_phone col-10 mb-1 small">#전화번호</div>
                        <input type="hidden" class="select_member_idx">
                    </a>
                </div>
                <div class="row px-4 pb-3">
                    <button class="btn btn-primary" onclick="alarmSelectMemberListClose();">닫기</button>
                </div>
            </div>
        </div>


        {{-- 저장 문구 목록 --}}
        <div class="tab-pane fade col-12 position-relative " id="alarm_v_main_layout_2" role="tabpanel"
            aria-labelledby="alarm_v_main_tab_2" tabindex="1">
            <div id="alarm_div_save_str" class="top-0 start-0 bottom-0 end-0 bg-white">
                {{-- TAb 알림톡 / SMS / PUSH --}}
                <ul id="alarm_ul_save_str" class="nav nav-tabs">
                    <li class="nav-item cursor-pointer">
                        <a class="nav-link active" onclick="alarmSaveStrTab(this);" type="kakao">알림톡</a>
                    </li>
                    <li class="nav-item cursor-pointer">
                        <a class="nav-link" onclick="alarmSaveStrTab(this);" type="sms">SMS</a>
                    </li>
                    <li class="nav-item cursor-pointer">
                        <a class="nav-link" onclick="alarmSaveStrTab(this);" type="push">Push</a>
                    </li>
                </ul>

                <h5 class="ps-3 mt-4 mb-2">저장 문구 목록</h5>
                <div class="px-4">
                    {{-- Table / checkbox / 구분 / 제목 / 내용 / 기능 --}}
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <td class="text-center" style="width:50px;">
                                    <input type="checkbox" onclick="alarmSaveStrAllChkbox(this);">
                                </td>
                                <td class="text-center">구분</td>
                                <td class="text-center">제목</td>
                                <td class="text-center" style="width:150px">내용</td>
                                <td class="text-center">이미지</td>
                                <td class="text-center">기능</td>
                            </tr>
                        </thead>
                        <tbody
                        {{-- id="alarm_tby_save_str" --}}
                        >
                            <tr
                            {{-- class="copy_tr_save_str" --}}
                            >
                                <td class="text-center">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                    <input type="checkbox" class="chk" hidden>
                                </td>
                                <td class="text-center" data="구분">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                    <span class="mform_type"></span>
                                </td>
                                <td class="text-center" data="#제목">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                    <span hidden>
                                        <span class="mform_title"></span>
                                    </span>
                                </td>
                                <td class="text-center" data="내용">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                    <span hidden>
                                        <span class="mform_content"></span>
                                    </span>
                                </td>
                                <td class="text-center" data="이미지">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>

                                    <span hidden>
                                        <img class="preview" style="width:50px;">
                                    </span>
                                </td>
                                <td class="text-center">
                                    <p class="card-text placeholder-glow loding_place mb-0">
                                        <span class="placeholder col-12"></span>
                                    </p>
                                    <div hidden>
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#alarm_div_modal_save_str_edit"
                                            onclick="alarmSaveStrEdit(this);">수정</button>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="alarmSaveStrGet(this);">사용</button>
                                    </div>
                                </td>
                                <input type="hidden" class="mform_seq">
                                <input type="hidden" class="img_data">
                                <input type="hidden" class="img_size">
                            </tr>
                        </tbody>
                    </table>
                    <div id="alarm_div_save_str_none" class="text-center" hidden>
                        <span>목록이 없습니다.</span>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between px-4">
                    <button class="btn btn-outline-primary" onclick="alarmSaveStrClose();"
                        style=" padding-bottom: 10px; ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                            <path
                                d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z" />
                            <path fill-rule="evenodd"
                                d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>


        {{-- 최근 발송 내역 --}}
        <div class="tab-pane fade col-12" id="alarm_v_main_layout_3" role="tabpanel"
            aria-labelledby="alarm_v_main_tab_3" tabindex="2">
            {{-- TAb 알림톡 / SMS / PUSH --}}
            <ul id="alarm_ul_last_send" class="nav nav-tabs">
                <li class="nav-item cursor-pointer">
                    <a class="nav-link active" onclick="alarmLastSendTab(this);" type="kakao">알림톡</a>
                </li>
                <li class="nav-item cursor-pointer">
                    <a class="nav-link" onclick="alarmLastSendTab(this);" type="sms">SMS</a>
                </li>
                <li class="nav-item cursor-pointer">
                    <a class="nav-link" onclick="alarmLastSendTab(this);" type="push">Push</a>
                </li>
            </ul>
            {{-- div > 검색단어 , input [회원명 또는 내용], start-date ~ end_date , 검색button --}}
            <div class="mt-2 d-flex align-items-center gap-2 px-4 mt-3">
                <span>검색단어</span>
                <input id="alarm_inp_last_search_str" type="text" class="form-control" style="width:250px;"
                    placeholder="회원명 또는 내용" onkeyup="if(event.keyCode == 13) alarmLastSendSelect()">


                <span class="search_category" hidden>구분</span>
                <select id="alarm_sel_category_sms" class="search_category form-select col-1" style="width:150px;"
                    hidden>
                    <option value="sms">단문</option>
                    <option value="lms">장문</option>
                    <option value="mms">이미지</option>
                </select>

                <span>발송일</span>
                <input id="alarm_inp_last_start_date" type="date" class="border rounded p-2" style="width:150px;"
                    value="{{ now()->subDays(7)->format('Y-m-d') }}">
                <span>~</span>
                <input id="alarm_inp_last_end_date" type="date" class="border rounded p-2" style="width:150px;"
                    value="{{ now()->format('Y-m-d') }}">
                <button id="alarm_btn_last_search" class="btn btn-outline-secondary"
                    onclick="alarmLastSendSelect();">
                    <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                    검색
                </button>
            </div>
            {{-- div > table > thead > tr > th > 구분 / 제목 / 내용 / 받는사람 / 발송일 / 전송상태 / 버튼 --}}
            <div class="px-4 mt-3 overflow-auto tableFixedHead border-top" style="height:100%;max-height:calc(100vh - 300px)">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th style="width:90px">구분</th>
                            <th style="width:80px">제목</th>
                            <td style="width:210px">내용</td>
                            <th style="width:80px">받는사람</th>
                            <th style="width:80px">발송일</th>
                            <th style="width:80px">전송상태</th>
                            <th style="width:60px">기능</th>
                        </tr>
                    </thead>
                    <tbody id="alarm_tby_last_send">
                        <tr class="copy_tr_last_send" hidden>
                            <td class="type" data="구분">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                            </td>
                            <td class="title" data="제목">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                            </td>
                            <td class="content" data="내용">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                            </td>
                            <td class="receiver" data="받는사람">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                            </td>
                            <td class="send_date" data="발송일">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                            </td>
                            <td data="전송상태">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                                <a class="send_status" href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#alarm_div_modal_last_status"
                                    onclick="alarmGetLastReportDetail(this)"></a>
                            </td>
                            <td>
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                                <button class="use_btn btn btn-sm btn-outline-secondary"
                                    onclick="alarmUseStrGet(this);" hidden>사용</button>
                            </td>
                            <input type="hidden" class="alarm_seq">
                        </tr>
                    </tbody>
                </table>
                <div id="alarm_div_last_send_none" class="text-center" hidden>
                    <span>목록이 없습니다.</span>
                </div>
            </div>


        </div>


        {{-- 예약 목록 --}}
        <div class="tab-pane fade col-12" id="alarm_v_main_layout_4" role="tabpanel"
            aria-labelledby="alarm_v_main_tab_4" tabindex="0">
            {{-- TAb 알림톡 / SMS / PUSH --}}
            <ul id="alarm_ul_reserv" class="nav nav-tabs">
                <li class="nav-item cursor-pointer">
                    <a class="nav-link active" onclick="alarmReservTab(this);" type="kakao">알림톡</a>
                </li>
                <li class="nav-item cursor-pointer">
                    <a class="nav-link" onclick="alarmReservTab(this);" type="sms">SMS</a>
                </li>
                <li class="nav-item cursor-pointer">
                    <a class="nav-link" onclick="alarmReservTab(this);" type="push">Push</a>
                </li>
            </ul>

            {{-- 날짜 검색 --}}
            <div class="mt-2 d-flex align-items-center gap-2 px-4 mt-3">
                <span>예약일</span>
                <input id="alarm_inp_reserv_start_date" type="date" class="border rounded p-2"
                    style="width:150px;" value="{{ now()->format('Y-m-d') }}">
                <span>~</span>
                <input id="alarm_inp_reserv_end_date" type="date" class="border rounded p-2" style="width:150px;"
                    value="{{ now()->addDays(7)->format('Y-m-d') }}">
                <button id="alarm_btn_reserv_search" class="btn btn-outline-secondary"
                    onclick="alarmReservSelect();">
                    <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                    검색
                </button>
            </div>
            {{-- 예약목록 / 구분 / 내용 / 첨부파일 / 받는 사람 / 예약일 / 기능 --}}
            <div class="px-4 mt-3">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>구분</th>
                            <th>내용</th>
                            <th>첨부파일</th>
                            <th>받는사람</th>
                            <th>예약일</th>
                            <th style="width:190px;">기능</th>
                        </tr>
                    </thead>
                    <tbody id="alarm_tby_reserv" class="align-middle">
                        <tr class="copy_tr_reserv">
                            <td class="type" data="구분">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                            </td>
                            <td class="content" data="내용">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                            </td>
                            <td class="img_data" data="첨부파일">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                                <img class="preview" style="width:50px;" hidden>
                            </td>
                            <td class="receiver" data="받는사람">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                            </td>
                            <td class="rev_date" data="예약일">
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                            </td>
                            <td>
                                <p class="card-text placeholder-glow loding_place mb-0"> <span
                                        class="placeholder col-12"></span> </p>
                                <div class="btn_div" hidden>
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#alarm_div_modal_reserv_edit"
                                        onclick="alarmReservEdit(this);">수정</button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="alarmReservCancel(this);">
                                        <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true"
                                            style="width:13px;height:13px;" hidden></span>
                                        예약 취소
                                    </button>
                                </div>
                            </td>
                            <input class="alarm_seq" type="hidden">
                            <input class="title" type="hidden">
                        </tr>
                    </tbody>
                </table>
                <div id="alarm_div_reserv_none" class="text-center" hidden>
                    <span>목록이 없습니다.</span>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // 문자 보내기 모달 열기
    function alarmSendSmsModalOpen(){
        //
        document.querySelectorAll('#alarm_modal_div_member_select .chk').forEach(function(el){
            el.checked = false;
        });
        const keys = Object.keys(select_member);
        if(keys.length > 0){
            keys.forEach(element => {
                const student_seq = select_member[element].student_seq;
                document.querySelectorAll('#alarm_modal_div_member_select .tr_student.student_seq_'+student_seq).forEach(function(el){
                    el.querySelector('.chk').checked = true;
                });
            });
            alarmSelectUserAddList();
        }
        // #alarm_modal_div_member_select
        const myModal = new bootstrap.Modal(document.getElementById('alarm_modal_div_member_select'), {
            keyboard: false,
            backdrop: 'static'
        });
        myModal.show();
    }

    // 기간설정 select onchange
    function alarmSelectDateType(vthis,start_date_tag, end_date_tag){
        const inp_start = document.querySelector(start_date_tag);
        const inp_end = document.querySelector(end_date_tag);

        // 0 = 기간설정 지난1주일 // end_date 에서 -7일을 start_date에 넣어준다.
        if(vthis.value == 0){
            const end_date = new Date(inp_end.value);
            end_date.setDate(end_date.getDate() - 7);
            inp_start.value = end_date.toISOString().substr(0, 10);
        }
        // onchage()
        inp_start.onchange();
        inp_end.onchange();
    }

        // 회원 가져오기
        function alarmSelectUser(is_main, page_num) {
        //버튼안에 스피너 로딩 보여주기
        const alarm_btn_sch = document.querySelector('#alarm_btn_sch');
        alarm_btn_sch.querySelector('.sp_loding').hidden = false;

        let alarm_div_member_select = document.querySelector('#alarm_modal_div_member_select');
        let tby_student = alarm_div_member_select.querySelector('.tby_student');
        let copy_tr = tby_student.querySelector('.copy_tr_student');
        if(is_main){
            alarm_div_member_select = document.querySelector('[data-secion-alarm-tab-sub="1"]');
            tby_student = alarm_div_member_select.querySelector('.tby_student');
            copy_tr = tby_student.querySelector('.copy_tr_student');
        }

        // 초기화 / 로딩 바를 tr이 아무것도 없으면 보여주기.
        if (document.querySelectorAll('.tr_student').length > 0)
            copy_tr.hidden = true;
        else {
            copy_tr.hidden = false;
            tby_student.innerHTML = '';
            tby_student.appendChild(copy_tr);
        }

        const user_type = is_main ? "":"";//alarm_div_member_select.querySelector('.sel_user_type').value;
        const alarm_sel_sch_type = document.querySelector('#alarm_sel_sch_type').value;
        const alarm_inp_sch_text = document.querySelector('#alarm_inp_sch_text').value;
        const alarm_sel_region = document.querySelector('#alarm_sel_region').value;
        const alarm_sel_team = document.querySelector('#alarm_sel_team').value;
        const grade = document.querySelector('#alarm_sel_sch_grade').value;

        const page = "/manage/alarm/studentlist";
        const parameter = {
            search_type: alarm_sel_sch_type,
            search_text: alarm_inp_sch_text,
            region_seq: alarm_sel_region,
            team_code: alarm_sel_team,
            user_type: user_type,
            grade: grade,
            page:page_num,
            page_max:10
        };
        if(!is_main) parameter.page_max = 100000;
        queryFetch(page, parameter, function(result) {
            alarm_btn_sch.querySelector('.sp_loding').hidden = true;
            if ((result.resultCode || '') == 'success') {
                if(is_main){
                    //페이징
                    tablePaging(result.student_info, '1');
                }
                copy_tr.hidden = true;
                tby_student.innerHTML = '';
                tby_student.appendChild(copy_tr);
                // 목록 가져오기
                result.student_info.data.forEach(function(el) {
                    const tr = copy_tr.cloneNode(true);
                    tr.classList.remove('copy_tr_student');
                    tr.classList.add('tr_student');
                    tr.hidden = false;
                    tr.querySelectorAll('.loding_place').forEach(function(el) {
                        el.remove();
                    });
                    tr.querySelector('.chk').hidden = false;
                    tr.querySelector('.group_type').innerText = el.group_name;
                    tr.querySelector('.student_name').innerText = el.student_name;
                    tr.querySelector('.student_id').innerText = '(' + el.student_id + ')';
                    tr.querySelector('.school_name').innerText = (el.school_name||'없음') + ' / ';
                    tr.querySelector('.student_phone').innerText = el.student_phone;
                    tr.querySelector('.grade').innerText = el.grade_name;
                    // tr.querySelector('.ticket').innerText = el.ticket_name;
                    //
                    tr.querySelector('.parent_name').innerText = (el.parent_name || '없음');
                    tr.querySelector('.parent_id').innerText = '(' + (el.parent_id || '없음') + ')';
                    tr.querySelector('.parent_phone').innerText = el.parent_phone;
                    tr.querySelector('.teach_name').innerText = el.teach_name;
                    //student_seq, parent_seq
                    tr.querySelector('.student_seq').value = el.id;
                    tr.querySelector('.parent_seq').value = el.parent_seq;
                    tr.classList.add('student_seq_'+el.id);
                    tby_student.appendChild(tr);
                    if(select_member[el.id]){
                        tr.querySelector('.chk').checked = true;
                    }
                });

                //목록이 없으면 목록이 없습니다. div 보여주기
                if (result.student_info.data.length == 0) {
                    const alarm_div_save_str_none = document.querySelector('#alarm_div_save_str_none');
                    alarm_div_save_str_none.hidden = false;
                }

            } else {
                sAlert('', '저장문구를 가져오는데 실패하였습니다.');
            }
        });
    }

        // 선택학생 목록
        function alarmSelStToggleList(vthis, is_main) {
            if (vthis.classList.contains('active')) {
                // 닫기 rotate-180
                vthis.classList.remove('active');
                vthis.classList.add('rotate-180');
                document.querySelector('[data-select-student-list-bundle'+(is_main?'':'2')+']').hidden = true;
            } else {
                // 열기
                vthis.classList.add('active');
                vthis.classList.remove('rotate-180');
                document.querySelector('[data-select-student-list-bundle'+(is_main?'':'2')+']').hidden = false;
            }
        }


            //문자보내기 > 학생체크시 배열에 넣기.
    function alarmSelMemberAdd(vthis, is_main){
        const tr = vthis.closest('tr');

        const student_seq = tr.querySelector('.student_seq').value;
        const parent_seq = tr.querySelector('.parent_seq').value;
        const student_id = tr.querySelector('.student_id').innerText;
        const parent_id = tr.querySelector('.parent_id').innerText;
        const student_name = tr.querySelector('.student_name').innerText;
        const parent_name = tr.querySelector('.student_name').innerText + ' 학부모';
        const grade = tr.querySelector('.grade').innerText;
        const student_phone = tr.querySelector('.student_phone').innerText;
        const parent_phone = tr.querySelector('.parent_phone').innerText;
        const push_key = tr.querySelector('.push_key').value;

        if(vthis.checked){
            select_member[student_seq] = {
                student_seq: student_seq,
                parent_seq: parent_seq,
                student_id: student_id,
                parent_id: parent_id,
                student_name: student_name,
                parent_name: parent_name,
                grade: grade,
                student_phone: student_phone,
                parent_phone: parent_phone,
                push_key: push_key
            };
            //체크이면 select_member에 넣기 단 중복체크는 안되게
            // let chk = false;
            // for(let i = 0; i < select_member.length; i++){
            //     if(select_member[i].student_seq == student_seq){
            //         chk = true;
            //         break;
            //     }
            // }
            // if(chk) return false;
            // const member = {
            //     student_seq: student_seq,
            //     parent_seq: parent_seq,
            //     student_id: student_id,
            //     parent_id: parent_id,
            //     student_name: student_name,
            //     parent_name: parent_name,
            //     grade: grade,
            //     student_phone: student_phone,
            //     parent_phone: parent_phone,
            //     push_key: push_key
            // };
            // select_member.push(member);
        }
        else{
            delete select_member[student_seq];
            //체크해제이면 select_member에서 빼기
            // for(let i = 0; i < select_member.length; i++){
            //     if(select_member[i].student_seq == student_seq){
            //         select_member.splice(i, 1);
            //         break;
            //     }
            // }
        }

        alarmSelectUserAddList(is_main);
        // 선택학생 외 몇명
        alrmSelectMemberCount();
    }

        // 배열을 바탕으로 선택 회원을 화면에 뿌려주는 함수
        function alarmSelectUserAddList(is_main) {
        //초기화 후
        let div_sel_member = document.querySelector('[data-select-student-list-bundle2]');
        let copy_sp_sel_member = div_sel_member.querySelector('[data-select-student-list-row2="clone"]').cloneNode(true);
        if(is_main){
            div_sel_member = document.querySelector('[data-select-student-list-bundle]');
            copy_sp_sel_member = div_sel_member.querySelector('[data-select-student-list-row="clone"]').cloneNode(true);
        }
        div_sel_member.innerHTML = '';
        div_sel_member.appendChild(copy_sp_sel_member);


        const keys = Object.keys(select_member);
        keys.some(function(el, idx) {
            const sp_sel_member = copy_sp_sel_member.cloneNode(true);
            sp_sel_member.classList.remove('copy_sp_sel_member');
            sp_sel_member.classList.add('sp_sel_member');
            sp_sel_member.hidden = false;
            // if (idx > 9) {
            //     sp_sel_member.innerText = "외 " + (select_member.length - 10) + "명";
            //     sp_sel_member.setAttribute('onclick', 'alarmSelectMemberList();');
            //     sp_sel_member.classList.add('text-primary');
            //     sp_sel_member.classList.add('cursor-pointer');
            //     div_sel_member.appendChild(sp_sel_member);

            //     //초기화 버튼 추가
            //     const btn_sel_member_clear = document.createElement('button');
            //     btn_sel_member_clear.classList.add('btn');
            //     btn_sel_member_clear.classList.add('btn-outline-danger');
            //     btn_sel_member_clear.classList.add('btn-sm');
            //     btn_sel_member_clear.classList.add('ml-2');
            //     btn_sel_member_clear.classList.add('btn_sel_member_clear');
            //     btn_sel_member_clear.innerText = '초기화';
            //     btn_sel_member_clear.setAttribute('onclick', 'alarmSelectMemberClear();');
            //     div_sel_member.appendChild(btn_sel_member_clear);
            //     return true;
            // }
            sp_sel_member.querySelector('[data-student-name]').innerText = select_member[el].student_name + '(' + select_member[el].grade + ')';
            sp_sel_member.setAttribute('data-student-seq', select_member[el].student_seq);
            div_sel_member.appendChild(sp_sel_member);

            //마지막일때 초기화 버튼 추가
            // if (idx == select_member.length - 1) {
            //     const sp_sel_member_clone = sp_sel_member.cloneNode(true);
            //     sp_sel_member_clone.innerText = "상세";
            //     sp_sel_member_clone.setAttribute('onclick', 'alarmSelectMemberList();');
            //     sp_sel_member_clone.classList.add('text-primary');
            //     sp_sel_member_clone.classList.add('cursor-pointer');
            //     div_sel_member.appendChild(sp_sel_member_clone);

            //     //초기화 버튼 추가
            //     const btn_sel_member_clear = document.createElement('button');
            //     btn_sel_member_clear.classList.add('btn');
            //     btn_sel_member_clear.classList.add('btn-outline-danger');
            //     btn_sel_member_clear.classList.add('btn-sm');
            //     btn_sel_member_clear.classList.add('ml-2');
            //     btn_sel_member_clear.classList.add('btn_sel_member_clear');
            //     btn_sel_member_clear.innerText = '초기화';
            //     btn_sel_member_clear.setAttribute('onclick', 'alarmSelectMemberClear();');
            //     div_sel_member.appendChild(btn_sel_member_clear);
            // }
        });

        //창 숨김 처리
        // alarm_div_member_select.hidden = true;
    }

    // 학생 선택 모달 오픈
    function alarmModalPrevStep(){

    }
    // 문자 전송 모달 오픈
    function alarmModalNextStep(){
        //select_member length 가 0이면 리턴
        // data-select-send-sel-post-type 가 sel 이면서
        if(select_member.length == 0 && document.querySelector('[data-select-send-sel-post-type]').value == 'sel'){
            toast('선택된 회원이 없습니다.');
            return false;
        }

        // input radio name=send_type 중 선택이 없으면 리턴
        const send_type = document.querySelector('input[name="send_type"]:checked');
        if(send_type == null){
            toast('발송유형을 선택해주세요.');
            return false;
        }

        // input radio name=send_target 중 선택이 없으면 리턴
        const send_target = document.querySelector('input[name="send_target"]:checked');
        if(send_target == null){
            toast('발송대상을 선택해주세요.');
            return false;
        }
        // SMS type 번호 체크.
        if(send_type.getAttribute('data-type') == 'sms' || send_type.getAttribute('data-type') == 'kakao'){
            alarmSelectSmsUserAddChk();
        }
        alarmSendFormTypeChk();
    }
    // 모달 닫고 보이기
    function alarmModalStep(type){
        if(type == 'prev'){
            // 모달 닫기
            document.querySelector('#alarm_modal_div_send').querySelector('.close-btn').click();
            // 이전 모달 열기
            const myModal = new bootstrap.Modal(document.getElementById('alarm_modal_div_member_select'), {
                keyboard: false
            });
            myModal.show();

        }else if(type == 'next'){
            // 모달 닫기
            document.querySelector('#alarm_modal_div_member_select .close-btn').click();
            // 다음 모달 열기
            const myModal = new bootstrap.Modal(document.getElementById('alarm_modal_div_send'), {
                keyboard: false
            });
            myModal.show();
        }
    }
    // 학생, 학부모, 모두 체크시 확인.
    function alarmSelectSmsUserAddChk() {
        const send_target = document.querySelector('input[name="send_target"]:checked').getAttribute('data-target');
        let is_none_phone = false;
        const keys = Object.keys(select_member);
        keys.forEach(function(member) {
            select_member[member].member_name = select_member[member].student_name +`(${select_member[member].grade})`;
            if(send_target == 'student'){
                if((select_member[member].student_phone||'') == ''){
                   is_none_phone = true;
                }
                select_member[member].send_type = send_target;
            }else{
                select_member[member].send_type = send_target;
            }
        });

        //학생이면서 번호가 없는경우
        if(is_none_phone){
            const title = `<span class="text-sb-20px scale-text-gray_05">발송 대상 확인</span>`;
            const msg1 = `<span class="text-sb-28px">등록된번호가 없는 학생이 있습니다.</span>`;
            const msg2 = `<p class="text-sb-28px text-danger pt-3">학부모 번호로 전송할까요?</p>`;
            sAlert(title, msg1 + msg2, 3,
            function(){
                keys.forEach(function(member) {
                    if((select_member[member].student_phone||'') == ''){
                        select_member[member].send_type = 'parent';
                    }else if((select_member[member].parent_phone||'') == ''){
                        select_member[member].send_type = '';
                    }
                    else{
                        select_member[member].send_type = 'student';
                    }
                });
                alarmModalStep('next');
            },
            function(){
                keys.forEach(function(member) {
                    if((select_member[member].student_phone||'') == ''){
                        select_member[member].send_type = '';
                    }else{
                        select_member[member].send_type = 'student';
                    }
                });
                alarmModalStep('next');
            }, '학부모 번호 전송', '선택 해제');
        }else{
            alarmModalStep('next');
        }
    }

    function alarmSendFormTypeChk(){
        // [data-select-send-sel-post-type] 의 select option text to [data-span-alarm-post-type] html
        const post_type = document.querySelector('[data-select-send-sel-post-type]');
        const span_alarm_post_type = document.querySelector('[data-span-alarm-post-type]');
        span_alarm_post_type.innerHTML = post_type.options[post_type.selectedIndex].text;

        // send_target getattr > [data-input-alarm-send-target] value
        const send_target = document.querySelector('input[name="send_target"]:checked');
        const input_alarm_send_target = document.querySelector('[data-input-alarm-send-target]');
        input_alarm_send_target.value = send_target.nextElementSibling.nextElementSibling.innerText;

        // [data-input-alarm-send-type]
        const send_type = document.querySelector('input[name="send_type"]:checked');
        const input_alarm_send_type = document.querySelector('[data-input-alarm-send-type]');
        input_alarm_send_type.value = send_type.nextElementSibling.nextElementSibling.innerText;

        const kko_code_el = document.querySelector('#alarm_modal_div_send .kko_code');
        if(send_type.dataset.type == 'kakao'){
            kko_code_el.hidden = false;
        }else{
            kko_code_el.hidden = true;
        }
    }

    // 선택학생 카운트
    function alrmSelectMemberCount(){
        if(select_member.length > 0){
            const post_type = document.querySelector('[data-select-send-sel-post-type]');
            post_type.value = 'sel';

            // 첫번째 학생 외 n명 post_type의 선택 option의 text 변경
            post_type.querySelector('option[value="sel"]').innerText =
            `${select_member[0].student_name}(${select_member[0].grade})` + (select_member.length != 1?` 외 ${select_member.length - 1}명`:'');
        }else{
            const post_type = document.querySelector('[data-select-send-sel-post-type]');
            post_type.querySelector('option[value="sel"]').innerText = '선택학생';
        }
    }

    // 문자보내기 > 선택 학생목록 > 삭제버튼
    function alarmSelMemberDel(vthis, is_main){
        const li = vthis.closest('li');
        const student_seq = li.getAttribute('data-student-seq');
        // for(let i = 0; i < select_member.length; i++){
        //     if(select_member[i].student_seq == student_seq){
        //         select_member.splice(i, 1);
        //         break;
        //     }
        // }
        delete select_member[student_seq];
        li.remove();
        let main_div = document.querySelector('#alarm_modal_div_member_select');
        if( is_main){
            main_div = document.querySelector('[data-secion-alarm-tab-sub="1"]');
        }
        // #tby_student .tr_student 중에 student_seq와 같은 tr을 찾아서 checked를 false로 변경
        main_div.querySelectorAll('.tby_student .tr_student').forEach(function(el){
            if(el.querySelector('.student_seq').value == student_seq){
                el.querySelector('.chk').checked = false;
            }
        });
        alrmSelectMemberCount();
    }

    // 발송예약하기 체크.
    function alarmReservCheck(vthis){
        if(vthis.checked){
            document.querySelector('[data-input-alarm-reserv="0"]').hidden = true;
            document.querySelector('[data-input-alarm-reserv="1"]').hidden = false;
        }else{
            document.querySelector('[data-input-alarm-reserv="0"]').hidden = false;
            document.querySelector('[data-input-alarm-reserv="1"]').hidden = true;
        }
    }

        // 전송 하기.
        function alarmSendMsg() {
        // 현제 알림톡인지 문자인지 푸시인지 체크 후
        // 각 함수로 전송.
        const type = document.querySelector('input[name="send_type"]:checked').getAttribute('data-type');

        //제목과 내용이 없으면 리턴
        const alarm_div_send = document.querySelector('#alarm_modal_div_send');
        const mfrom_title = alarm_div_send.querySelector('.mform_title').value;
        const mform_content = alarm_div_send.querySelector('.mform_content').value;
        const kko_code = alarm_div_send.querySelector('.kko_code').value;
        const url_str = alarm_div_send.querySelector('.url').value;
        let rev_date = '';
        //alarm_chk_reserv
        if (document.querySelector('#alarm_chk_reserv').checked == true) {
            rev_date = document.querySelector('#alarm_rev_date').value + ' ' + document.querySelector('#alarm_rev_time')
                .value + ':00';
            //rev_time 이 현재 시간보다 작으면 리턴
            if (rev_date < new Date().format('yyyy-MM-dd HH:mm:ss')) {
                sAlert('', '예약시간이 현재시간보다 작습니다.');
                return false;
            }
            //-와 : 그리고 공백을 제외.
            //단 알림톡(카카오)일경우 하지 않는다.
            if(type != 'kakao')
                rev_date = rev_date.replace(/-/g, '').replace(/:/g, '').replace(/ /g, '');
        }

        if (mform_content == '') {
            sAlert('', '내용을 입력해주세요.');
            return false;
        }


        let img_data = document.querySelector('#alarm_img_file').getAttribute('src');
        if (img_data.length > 0)
            img_data = img_data.substr(img_data.indexOf(',') + 1);

        //select_member 배열이 하나라도 없으면 리턴
        if (select_member.length == 0) {
            sAlert('', '선택 회원이 없습니다.');
            return false;
        }
        //sms_type 타입 / alarm_sp_mtype 단문이면 sms / 장문이면 mms
        const sms_type = document.querySelector('#alarm_sp_mtype').innerText == '단문' ? 'sms' : 'mms';
        const page = "/manage/send/" + type;
        const parameter = {
            mform_title: mfrom_title,
            mform_content: mform_content,
            select_member: select_member,
            img_data: img_data,
            sms_type: sms_type,
            send_length: select_member.length,
            rev_date: rev_date,
            kko_code: kko_code,
            url_str: url_str
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                sAlert('', '<span class="text-sb-28px">전송되었습니다.</span>', 4);
            } else {
                sAlert('', `<span class="text-sb-28px">${result.resultMsg}</span>`, 4);
            }
        });
    }
    //----------------------------------------------

    // 회원 선택 SELECT ONCHANGE
    function alarmSelectMember(vthis) {
        //value sel_member 일때만 하단 버튼이 보이게 / 아니면 숨기기
        const alarm_div_send = document.querySelector('#alarm_modal_div_send');
        const btn_get_member = alarm_div_send.querySelector('.btn_get_member');
        btn_get_member.hidden = true;
        if (vthis.value == 'sel_member') {
            btn_get_member.hidden = false;
        } else if (vthis.value == 'coming_expire') {
            //이용권 만료 한달전 ~ 만료전
        } else if (vthis.value == 'expire_member') {
            //만료회원
        }
    }

    // 회원 선택하기 버튼 클릭시
    function alarmGetMember() {
        const alarm_div_member_select = document.querySelector('#alarm_div_member_select');
        alarm_div_member_select.hidden = false;
    }

    // 목록(저장문구) 내용 가져오기  창 닫기
    function alarmSaveStrClose() {
        const alarm_div_save_str = document.querySelector('#alarm_div_save_str');
        // alarm_div_save_str.hidden = false;
        //초기화
        const alarm_tby_save_str = document.querySelector('#alarm_tby_save_str');
        const copy_tr = alarm_tby_save_str.querySelector('.copy_tr_save_str');
        alarm_tby_save_str.innerHTML = '';
        copy_tr.hidden = false;
        alarm_tby_save_str.appendChild(copy_tr);
        copy_tr.querySelectorAll('.loding_place').forEach(function(el) {
            el.hidden = false;
            el.nextElementSibling.hidden = true;
        });
        //목록 가져오기
        alarmSaveStrSelect();

    }

    // 목룍(저장문구) 내용 가져오기 창 열기
    function alarmSaveStrOpen() {
        // #alarm_modal_div_save_str_list
        // 모달에 정보 가져오기.
        const mform_type = document.querySelector('[data-select-alarm-save-list2]').value;
        alarmSaveStrSelect(mform_type);
        const myModal = new bootstrap.Modal(document.getElementById('alarm_modal_div_save_str_list'), {
            keyboard: false
        });
        myModal.show();
    }

    //목록(저장문구)에 내용 저장.
    function alarmMessageFormSave() {
        const alarm_div_send = document.querySelector('#alarm_modal_div_send');
        const mform_title = alarm_div_send.querySelector('.mform_title').value;
        const mform_content = alarm_div_send.querySelector('.mform_content').value;
        const img_data = document.querySelector('#alarm_img_file').src;
        const img_size = document.querySelector('#alarm_div_imgsize').innerText;
        let mform_type = document.querySelector('input[name="send_type"]:checked').getAttribute('data-type')
        const url_str = alarm_div_send.querySelector('.url').value;
        const kko_code = alarm_div_send.querySelector('.kko_code').value;
        // 문자면 장문인지 단문인지 확인

        //타이틀이나 내용이 없으면 저장 안되게
        if (mform_title == '' || mform_content == '') {
            sAlert('', '제목이나 내용을 입력해주세요.');
            return false;
        }
        const page = "/manage/messageinsert";
        const parameter = {
            mform_title: mform_title,
            mform_content: mform_content,
            mform_type: mform_type,
            img_data: img_data,
            img_size: img_size,
            url_str:url_str,
            kko_code:kko_code
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                sAlert('', '<span class="text-sb-28px">저장되었습니다.</span>', 4);
            } else {
                sAlert('', '<span class="text-sb-28px">저장에 실패하였습니다.</span>', 4);
            }
        });
    }

    // 목록(저장문구) 내용 가져오기2
    function alarmSaveStrGetDetail(){
        // data-input-alarm-save-str-title
        // data-input-alarm-save-str-content
        // 의 글자가 있는지 확인하고 없으면 리턴
        const title = document.querySelector('[data-input-alarm-save-str-title]').value;
        const content = document.querySelector('[data-input-alarm-save-str-content]').value;
        const kko_code = document.querySelector('[data-input-alarm-save-kko-code]').value;
        const url_str = document.querySelector('[data-input-alarm-save-url-str]').value;

        if(title.length < 1 || content.length < 1){
            toast('사용하실 문구를 선택하거나 입력해주세요.');
            return;
        }
        document.querySelector('#alarm_modal_div_save_str_list .close-btn').click();
        document.querySelector('[data-input-alarm-save-str-title]').value = '';
        document.querySelector('[data-input-alarm-save-str-content]').value = '';
        document.querySelector('[data-input-alarm-save-kko-code]').value = '';
        document.querySelector('[data-input-alarm-save-url-str]').value = '';

        const modal = document.querySelector('#alarm_modal_div_send');
        modal.querySelector('.mform_title').value = title;
        modal.querySelector('.mform_content').value = content;
        modal.querySelector('.url').value = url_str;
        modal.querySelector('.kko_code').value = kko_code;
    }

    // 목록(저장문구) 내용 가져오기1 추후 이미지에 대해서 코드 복사를 위해 남겨놓음.
    function alarmSaveStrGet(vthis) {
        //alarm_v_main_tab_1 클릭 / 탭이동
        document.querySelector('#alarm_v_main_tab_1').click();
        //이미지 초기화
        const alarm_img_file = document.querySelector('#alarm_img_file');
        alarm_img_file.src = '';
        const alarm_inp_imgfile = document.querySelector('#alarm_inp_imgfile');
        alarm_inp_imgfile.value = '';
        // 용량 초기화
        const alarm_div_imgsize = document.querySelector('#alarm_div_imgsize');
        alarm_div_imgsize.innerText = '';

        // 제목, 내용 가져오기
        const tr = vthis.closest('tr');
        const mform_title = tr.querySelector('.mform_title').innerText;
        const mform_content = tr.querySelector('.mform_content').innerText;
        const img_data = tr.querySelector('.img_data').value;
        const img_size = tr.querySelector('.img_size').value;
        const kko_code = tr.querySelector('.kko_code').innerText;
        const url_str = tr.querySelector('.url').value;

        // 가져온 내용 넣기
        const alarm_div_send = document.querySelector('#alarm_modal_div_send');
        alarm_div_send.querySelector('.mform_title').value = mform_title;
        alarm_div_send.querySelector('.mform_content').value = mform_content;
        alarm_img_file.src = img_data;
        alarm_div_imgsize.innerText = img_size;
        alarm_div_send.querySelector('.kko_code').value = kko_code;
        alarm_div_send.querySelector('.url').value = url_str;

        toast('문구가 전송내용에 적용되었습니다.');

        // 문자 보내기 창 띄우기.
        alarmSendSmsModalOpen();
        const mform_type = document.querySelector('[data-select-alarm-save-list]').value;
        const send_type = document.querySelector(`input[name="send_type"][data-type="${mform_type}"]`);
        send_type.checked = true;
    }

    function alarmUseStrGet(vthis){
        // 제목, 내용 가져오기
        const tr = vthis.closest('tr');
        const mform_title = tr.querySelector('.title').innerText;
        const mform_content = tr.querySelector('.content').innerText;

        const alarm_div_send = document.querySelector('#alarm_modal_div_send');
        alarm_div_send.querySelector('.mform_title').value = mform_title;
        alarm_div_send.querySelector('.mform_content').value = mform_content;
        alarm_img_file.src = '';
        alarm_div_imgsize.innerText = '';

        toast('문구가 전송내용에 적용되었습니다.');
    }

    // 문자 내용 초기화
    function alarmMessageFormClear() {
        sAlert('', '내용을 초기화 하시겠습니까?', 2, function() {
            alarmMessageFormClearIn();
        });
    }
    function alarmMessageFormClearIn(){
        const alarm_div_send = document.querySelector('#alarm_modal_div_send');
        alarm_div_send.querySelector('.mform_title').value = '';
        alarm_div_send.querySelector('.mform_content').value = '';
        alarm_div_send.querySelector('.kko_code').value = '';
        alarm_div_send.querySelector('.url').value = '';

        // 이미지 초기화
        const alarm_img_file = document.querySelector('#alarm_img_file');
        alarm_img_file.src = '';
        const alarm_inp_imgfile = document.querySelector('#alarm_inp_imgfile');
        alarm_inp_imgfile.value = '';

        // 용량 초기화
        const alarm_div_imgsize = document.querySelector('#alarm_div_imgsize');
        alarm_div_imgsize.innerText = '';
    }

    // 문자 내용 수정 모달 초기화
    function alarmMessageEditModalClear() {
        const alarm_div_modal_save_str_edit = document.querySelector('#alarm_div_modal_save_str_edit');
        alarm_div_modal_save_str_edit.querySelector('.mform_seq').value = '';
        alarm_div_modal_save_str_edit.querySelector('#alarm_recipient-name').value = '';
        alarm_div_modal_save_str_edit.querySelector('#alarm_message-text').value = '';
    }

    // 목록(저장문구) 전체 체크박스
    function alarmSaveStrAllChkbox(vthis) {
        const alarm_tby_save_str = document.querySelector('#alarm_tby_save_str');
        alarm_tby_save_str.querySelectorAll('.tr_save_str .chk').forEach(function(el) {
            el.checked = vthis.checked;
        });
    }

    // 목록(저장문구) 선택한 문구 삭제
    function alarmSaveStrDelete(is_main) {
        //체크박스의 mform_seq 가져오기
        let mform_seqs = '';
        let alarm_tby_save_str = document.querySelector('#alarm_tby_save_str2');
        if(is_main)
            alarm_tby_save_str = document.querySelector('#alarm_tby_save_str');
        alarm_tby_save_str.querySelectorAll('.tr_save_str .chk').forEach(function(el) {
            const tr = el.closest('tr');
            const m_seq = tr.querySelector('.mform_seq').value;
            if (el.checked) {
                if (mform_seqs != '')
                    mform_seqs += ',';
                mform_seqs += m_seq;
            }
        });

        // 삭제할 문구 목록이 없으면 리턴
        if (mform_seqs == '') {
            sAlert('', '삭제할 문구 목록을 선택해주세요.');
            return false;
        }

        // 삭제할 문구 목록이 있으면 삭제
        const page = "/manage/messagedelete";
        const parameter = {
            mform_seqs: mform_seqs,
        };

        sAlert('', '선택한 문구를 삭제하시겠습니까?', 2, function() {
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    sAlert('', '삭제되었습니다.');

                    //목록(저장문구) 가져오기. / table
                    // 현제 페이지 가져오기.
                    if(is_main){
                        // [data-ul-alarm-page="2"] .page_num.active
                        const mform_type = document.querySelector('[data-select-alarm-save-list]').value;
                        const current_page = document.querySelector('[data-ul-alarm-page="2"] .page_num.active').innerText;
                        alarmSaveStrSelect(mform_type, current_page);
                    }else{
                        const mform_type = document.querySelector('[data-select-alarm-save-list2]').value;
                        alarmSaveStrSelect(mform_type);
                    }
                } else {
                    toast('삭제에 실패하였습니다.');
                }
            });
        });
    }

    // 이미지 선택시 리뷰 img에 미리보기
    function alarmImgFileChange(vthis) {
        const img_src_str = document.querySelector('[data-input-file-upload-img-str]');
        const alarm_img_file = document.querySelector('#alarm_img_file');
        const file = vthis.files[0];
        //파일 직접 경로.
        img_src_str.value = vthis.value;
        const reader = new FileReader();
        reader.onload = function() {
            alarm_img_file.src = reader.result;
        }
        reader.readAsDataURL(file);

        //용량 계산 후 alarm_div_imgsize 에 넣기
        const alarm_div_imgsize = document.querySelector('#alarm_div_imgsize');
        const size = file.size;

        //용량이 300kb 이상이면 리턴
        if (size > 300000) {
            alarm_div_imgsize.innerText = '용량이 300KB 이상입니다.';
            return false;
        }

        //용량 계산
        let size_text = '';
        if (size > 1000000) {
            size_text = (size / 1000000).toFixed(2) + 'MB';
        } else if (size > 1000) {
            size_text = (size / 1000).toFixed(2) + 'KB';
        } else {
            size_text = size + 'B';
        }
        alarm_div_imgsize.innerText = size_text;
    }

    // 저장 문구 목록에서 이미지 수정 // (함수가 중복이긴 하나 2개 정도라 굳이..)
    function alarmUpdateImgFileChange(vthis, img_id) {
        const alarm_img_file = document.querySelector('#' + img_id);
        const file = vthis.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(file);
        const size = file.size;
        reader.onload = function() {
            if (size > 300000) {
                alarm_img_file.src = '';
            }else
                alarm_img_file.src = reader.result;
        }

        //용량이 300kb 이상이면 리턴
        if (size > 300000) {
            toast('용량이 300KB 이상입니다.');
            alarm_img_file.src = '';
            vthis.value = '';
            return false;
        }
    }

    // 이미지 클릭시 이미지 초기화
    function imgClear(vthis) {
        vthis.src = '';
        const alarm_inp_imgfile = document.querySelector('#alarm_inp_imgfile');
        alarm_inp_imgfile.value = '';

        //용량 초기화
        const alarm_div_imgsize = document.querySelector('#alarm_div_imgsize');
        alarm_div_imgsize.innerText = '';
    }

    // 문자 내용 입력시 바이트 계산
    function alarmContentByteSize(vthis) {
        const string = vthis.value;
        var sl = (function(s, b, i, c) {
            for (b = i = 0; c = s.charCodeAt(i++); b += c >> 11 ? 2 : c >> 7 ? 2 : 1);
            return b
        })(string);
        const alarm_sp_mtype = document.querySelector('#alarm_sp_mtype');
        const alarm_sp_bite = document.querySelector('#alarm_sp_bite');
        if (sl > 90) {
            alarm_sp_mtype.innerHTML = '장문';
            alarm_sp_bite.innerHTML = sl + '/2000';
        } else {
            alarm_sp_mtype.innerHTML = '단문';
            alarm_sp_bite.innerHTML = sl + '/90';
        }
    }

    //소속 변경 시 팀 select box에 표기
    function alarmSelectRegion(nThis) {
        const sel_val = nThis.value;
        const sel_div = nThis.closest('div');
        sel_div.querySelector('.team').selectedIndex = 0;
        const divTeamList = sel_div.querySelectorAll('.team option');
        divTeamList.forEach((idx) => {
            if (idx.getAttribute('region') == null) {
                idx.hidden = false;
            } else if (sel_val == idx.getAttribute('region')) {
                idx.hidden = false;
            } else {
                idx.hidden = true;
            }
        });
    }

    //
    function getGubun(group_type) {

    }

    // 회원 가져오기 창 닫기
    function alarmSelectUserClose() {
        //alarm_inp_sch_text
        const alarm_div_member_select = document.querySelector('#alarm_div_member_select');
        const alarm_inp_sch_text = alarm_div_member_select.querySelector('#alarm_inp_sch_text');
        const alarm_sel_region = alarm_div_member_select.querySelector('#alarm_sel_region');
        const alarm_sel_team = alarm_div_member_select.querySelector('#alarm_sel_team');
        const tby_student = alarm_div_member_select.querySelector('.tby_student');
        const copy_tr = tby_student.querySelector('.copy_tr_student').cloneNode(true);

        //창 숨김 처리
        alarm_div_member_select.hidden = true;

        //초기화
        tby_student.innerHTML = '';
        copy_tr.hidden = true;
        tby_student.appendChild(copy_tr);
        alarm_sel_region.selectedIndex = 0;
        alarm_sel_team.selectedIndex = 0;
        alarm_inp_sch_text.value = '';

    }

    // 회원 가져오기 창에서 회원 선택하기 버튼 클릭시
    function alarmMemSelectType(vthis) {
        //내부 옵션이 grade이면 alarm_sel_sch_grade 보여주기 / 아니면 숨기기
        const alarm_sel_sch_grade = document.querySelector('#alarm_sel_sch_grade');
        const alarm_inp_sch_text = document.querySelector('#alarm_inp_sch_text');
        alarm_sel_sch_grade.hidden = true;
        if (vthis.value == 'grade') {
            alarm_sel_sch_grade.hidden = false;
            alarm_inp_sch_text.hidden = true;
        } else {
            alarm_sel_sch_grade.hidden = true;
            alarm_inp_sch_text.hidden = false;
        }
    }

    // 회원 가져오기 창에서 회원 전체 체크박스 클릭시
    function alarmSelectUserAllChkbox(vthis, is_main) {
        let tby_student = document.querySelector('#alarm_div_member_select .tby_student');
        let chk_cnt = tby_student.querySelectorAll('.tr_student .chk:checked').length;
        if(is_main){
            tby_student = document.querySelector('[data-secion-alarm-tab-sub="1"]').querySelector('.tby_student');
            chk_cnt = tby_student.querySelectorAll('.tr_student .chk:checked').length;
        }
        tby_student.querySelectorAll('.tr_student .chk').forEach(function(el) {
            el.checked = vthis.checked;
            //onchage 이벤트 발생
            el.onchange();
        });
        document.querySelector('#alarm_sp_sel_user_cnt').innerText = chk_cnt;
    }


    // 회원 가져오기 창에서 선택 회원 추가 버튼 클릭시
    var select_member = {};
    function alarmSelectUserAdd(is_parent) {
        // 학생 / 학부모 체크 라디오 모두 체크되어있는지 확인
        const radio_student = document.querySelector('#alarm_radio_student');
        const radio_parent = document.querySelector('#alarm_radio_parent');
        const is_radio_student = radio_student.checked;
        const is_radio_parent = radio_parent.checked;
        //라디오라서 해제상태는 없겠지만 우선은 추가.
        if (!radio_student.checked && !radio_parent.checked) {
            sAlert('', '학생 / 학부모 중 누구에게 보낼지 선택해주세요.');
            return false;
        }

        if (!alarmSelectUserAddChk(is_parent) && is_radio_student) {
            return;
        }
        // 체크가 되어있는지 확인 없으면 리턴 / 먼저 학생을 선택해주세요.
        const tby_student = document.querySelector('#alarm_div_member_select .tby_student');
        const tr_student = tby_student.querySelectorAll('.tr_student');
        const chk_cnt = tby_student.querySelectorAll('.tr_student .chk:checked').length;
        if (chk_cnt == 0) {
            sAlert('', '먼저 학생을 선택해주세요.');
            return false;
        }

        //배열 select_member에 초기화 후 선택 회원을 배열에 추가.
        select_member = [];
        let none_cnt = 0;
        tr_student.forEach(function(el) {
            //학생일 수도 있고 학부모일 수도 있으므로 member로 통일
            const chk = el.querySelector('.chk:checked');
            if (chk == null) return false;

            const member_id = is_radio_student ? el.querySelector('.student_id').innerText : el.querySelector('.parent_id').innerText;
            const member_name = el.querySelector('.student_name').innerText + (is_radio_parent ? ' 학부모':'');
            const grade = el.querySelector('.grade').innerText;
            const student_phone = el.querySelector('.student_phone').innerText;
            const parent_phone = el.querySelector('.parent_phone').innerText;
            const push_key = el.querySelector('.push_key').value;

            //학생일때 학생 휴대번호가 없으면 학부모 휴대번호로 대체
            let phone = (is_parent || false) && (student_phone || '') == '' ? parent_phone : student_phone;
            //단 라디오버튼(전송타겟)이 학부모면 학부모 휴대번호로 대체
            phone = is_radio_parent ? parent_phone : phone;

            const member = {
                member_id: member_id,
                member_name: member_name ,
                grade: grade,
                phone: phone,
                push_key: push_key,
            };
            select_member.push(member);
        });

        // 배열을 바탕으로 선택 회원을 화면에 뿌려주는 함수
        alarmSelectUserAddList();
    }

    // 회원 가져오기 창에서 checkbox 변환시
    function alarmSelectUserChkbox() {
        const tby_student = document.querySelector('#alarm_div_member_select .tby_student');
        const chk_cnt = tby_student.querySelectorAll('.tr_student .chk:checked').length;
        document.querySelector('#alarm_sp_sel_user_cnt').innerText = chk_cnt;
    }

    // 외 ~명 클릭시
    function alarmSelectMemberList() {
        const alarm_div_select_member = document.querySelector('#alarm_div_select_member');
        const copy_member_list = alarm_div_select_member.querySelector('.copy_member_list').cloneNode(true);
        const select_member_list = alarm_div_select_member.querySelector('.select_member_list');
        select_member_list.innerHTML = '';
        select_member_list.appendChild(copy_member_list);

        const member_cnt = select_member.length;
        alarm_div_select_member.querySelector('.member_cnt').innerText = member_cnt + '명';
        const keys = Object.keys(select_member);
        keys.forEach(function(el, index) {
            const member_list = copy_member_list.cloneNode(true);
            member_list.classList.remove('copy_member_list');
            member_list.classList.add('member_list');
            member_list.hidden = false;
            member_list.querySelector('.member_name').innerText = select_member[el].member_name + '(' + select_member[el].grade + ')';
            member_list.querySelector('.member_phone').innerText = select_member[el].phone;
            member_list.querySelector('.select_member_idx').value = index;
            select_member_list.appendChild(member_list);
        });
        alarm_div_select_member.hidden = false;
    }

    // 외 ~명 클릭후 창 닫기
    function alarmSelectMemberListClose() {
        const alarm_div_select_member = document.querySelector('#alarm_div_select_member');
        alarm_div_select_member.hidden = true;
    }

    // 최근 발송내역 상태 형태
    function alarmGetLastStatus(data) {
        const type = data.type;
        const suc_cnt = data.succ_count;
        const fail_cnt = data.receiver_cnt - suc_cnt;
        let status = '';
        const sms_type = ['sms', 'lms', 'mms', 'kakao'];
        if (sms_type.indexOf(type) > -1) {
            status =
                (suc_cnt > 0 ? '성공(' + suc_cnt + ')' : '') + ' ' +
                (fail_cnt > 0 ? '실패(' + fail_cnt + ')' : '');
        }

        return status;
    }




    // 전송 내역 상세 > 검색후 내부 검색.
    function alarmLastStatusSelect() {
        const alarm_tby_last_report = document.querySelector('#alarm_tby_last_report');
        const tr_list = alarm_tby_last_report.querySelectorAll('.tr_last_report');
        const suc_chk = document.querySelector('#alarm_chk_last_status_success').checked;
        const fail_chk = document.querySelector('#alarm_chk_last_status_fail').checked;
        const search_str = document.querySelector('#alarm_inp_last_status_search_str').value;
        const spin_loding = document.querySelector('#alarm_btn_last_status_search .sp_loding');
        spin_loding.hidden = false;

        tr_list.forEach(function(el) {
            el.hidden = true;
            const content = el.querySelector('.content').innerText;
            const recr_name = el.querySelector('.recr_name').innerText;
            const recr_phone = el.querySelector('.recr_phone').innerText;
            const send_status = el.querySelector('.send_status').innerText;
            if (content.indexOf(search_str) > -1 ||
                recr_name.indexOf(search_str) > -1 ||
                recr_phone.indexOf(search_str) > -1 ||
                send_status.indexOf(search_str) > -1) {
                if (suc_chk && send_status == '성공' ||
                    fail_chk && send_status == '실패' ||
                    send_status == '전송대기' ||
                    send_status == '기타') {
                    el.hidden = false;
                }
            }
        });
        spin_loding.hidden = true;
    }



    // 전송 내역 상세에서 전체 체크박스 클릭시
    function alarmLastStatusAllChkbox(vthis) {
        const this_chk = vthis.checked;
        const alarm_tby_last_report = document.querySelector('#alarm_tby_last_report');
        alarm_tby_last_report.querySelectorAll('.tr_last_report .chk').forEach(function(el) {
            // 단 hidden 이 아닌것만 체크
            if (el.closest('tr').hidden == false) el.checked = this_chk;
        });
    }




    // 선택학생 select_member 에서 삭제
    function alarmSelectMemberDelete(vthis){
        const sp_sel_member = vthis.closest('.member_list');
        const idx = sp_sel_member.querySelector('.select_member_idx').value;
        sp_sel_member.remove();
        select_member.splice(idx, 1);
        alarmSelectUserAddList();
        //select_member_idx 재정렬
        const select_member_list = document.querySelector('.select_member_list');
        const member_list = select_member_list.querySelectorAll('.member_list');
        member_list.forEach(function(el, idx){
            el.querySelector('.select_member_idx').value = idx;
        });
    }

    // 초기화
    function alarmSelectMemberClear(){
        alarmSelectMemberListClose();
        select_member = [];
        alarmSelectUserAddList();
    }

    // grade 분류에 따른 이름 가져오기.
    function alarmGetGrade(code_seq){
        if((code_seq||'') == '') return '';
        const grade = document.querySelector('#alarm_sel_grade_codes');
        const grade_name = grade.querySelector('option[value="'+code_seq+'"]').innerText;
        return grade_name;
    }
            //목록(저장문구) 가져오기.
            function alarmSaveStrSelect(mform_type, is_main, page_num) {
            // const mform_type = document.querySelector('#alarm_ul_save_str .active').getAttribute('type');
            const page = "/manage/messagelist";
            const parameter = {
                mform_type: mform_type,
                page: page_num
            };
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    if(is_main){
                        // 페이징
                        tablePaging(result.message_form_info, '2');
                    }
                    // 초기화
                    let alarm_tby_save_str = document.querySelector('#alarm_tby_save_str2');
                    if(is_main)
                        alarm_tby_save_str = document.querySelector('#alarm_tby_save_str');

                    const copy_tr = alarm_tby_save_str.querySelector('.copy_tr_save_str');
                    alarm_tby_save_str.innerHTML = '';
                    copy_tr.hidden = true;
                    alarm_tby_save_str.appendChild(copy_tr);

                    // 목록 가져오기
                    result.message_form_info.data.forEach(function(el) {
                        const tr = copy_tr.cloneNode(true);
                        tr.classList.remove('copy_tr_save_str');
                        tr.classList.add('tr_save_str');
                        tr.hidden = false;
                        // tr.querySelectorAll('.loding_place').forEach(function(el) {
                        //     el.nextElementSibling.hidden = false;
                        //     el.remove();
                        // });
                        tr.querySelector('.mform_seq').value = el.id;
                        tr.querySelector('.mform_type').innerText = el.mform_type;
                        tr.querySelector('.mform_title').innerText = el.mform_title;
                        tr.querySelector('.mform_content').innerText = el.mform_content;
                        tr.querySelector('.preview').src = el.img_data || '';
                        tr.querySelector('.img_data').value = el.img_data;
                        tr.querySelector('.img_size').value = el.img_size;
                        tr.querySelector('.chk').value = el.mform_idx;
                        tr.querySelector('.url').value = el.url;
                        tr.querySelector('.kko_code').innerText = el.kko_code;
                        if(is_main){
                            tr.querySelector('.chk').hidden = false;
                            tr.querySelector('.btn').hidden = false;
                        }
                        alarm_tby_save_str.appendChild(tr);
                    });

                    //목록이 없으면 목록이 없습니다. div 보여주기
                    // if (result.message_form_info.data.length == 0) {
                    //     const alarm_div_save_str_none = document.querySelector('#alarm_div_save_str_none');
                    //     alarm_div_save_str_none.hidden = false;
                    // }

                } else {
                    sAlert('', '저장문구를 가져오는데 실패하였습니다.');
                }
            });
        }

        // 목록에서 내용 가져오기 TR CLICK
        function alarmTrSaveStr(vthis){
            if(vthis.classList.contains('active')){
                vthis.classList.remove('active');
                document.querySelector('[data-input-alarm-save-str-title]').value = '';
                document.querySelector('[data-input-alarm-save-str-content]').value = '';
                document.querySelector('[data-input-alarm-save-kko-code]').value = '';
                document.querySelector('[data-input-alarm-save-url-str]').value = '';
            }
            else{
                const tbody = vthis.closest('tbody');
                tbody.querySelectorAll('tr.active').forEach(function(el){
                    el.classList.remove('active');
                });
                vthis.classList.add('active');
                const title = vthis.querySelector('.mform_title').innerText;
                const content = vthis.querySelector('.mform_content').innerText;
                const kko_code = vthis.querySelector('.kko_code').innerText;
                const url_str = vthis.querySelector('.url').value;
                document.querySelector('[data-input-alarm-save-str-title]').value = title;
                document.querySelector('[data-input-alarm-save-str-content]').value = content;
                document.querySelector('[data-input-alarm-save-kko-code]').value = kko_code;
                document.querySelector('[data-input-alarm-save-url-str]').value = url_str;
            }
        }

    // 학생의 seq 를 넘겨서 학부모정보까지 모두 가져오기.
    async function alarmSendGetSmsInfo(student_seqs){
        const page = "/manage/alarm/send/user/info";
        const parameter = {
            student_seqs: student_seqs
        };

        return new Promise((resolve, reject) => {
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    select_member = result.user_infos;
                    resolve(true);
                } else {
                    reject(false);
                }
            });
        });
    }

    // 목록 저장할때, 저장 형식 이 바뀔때.
    function alarmSaveChgSelectType(vthis){
        const type = vthis.value;
        const kko_code_el = document.querySelector('[data-input-alarm-save-kko-code]');
        const kko_columns = document.querySelectorAll('.kko_column');
        if(type == 'kakao'){
            kko_code_el.hidden = false;
            kko_columns.forEach(function(el){
                el.hidden = false;
            });
        }else{
            kko_code_el.hidden = true;
            kko_columns.forEach(function(el){
                el.hidden = true;
            });
        }
        alarmSaveStrSelect(type);
    }
    // 전체학생, 선택학생.
    function alarmSendChgSelectType(){
        const select = document.querySelector('[data-select-send-sel-post-type]');
        const sel_val = select.value;
        const all_div = document.querySelector('[data-student-all-div-select]');
        const sel_div = document.querySelector('[data-student-sel-div-select]');
        if(sel_val == 'sel'){
            all_div.hidden = true;
            sel_div.classList.add('d-flex');
            sel_div.hidden = false;
            sel_div.querySelectorAll('.tr_student input[type=checkbox]').forEach(function(el){
                el.checked = false;
                el.onchange();
            });
        }else if(sel_val == 'all'){
            all_div.hidden = false;
            sel_div.classList.remove('d-flex');
            sel_div.hidden = true;
            sel_div.querySelectorAll('.tr_student input[type=checkbox]').forEach(function(el){
                el.checked = true;
                el.onchange();
            });
        }
    }


</script>
