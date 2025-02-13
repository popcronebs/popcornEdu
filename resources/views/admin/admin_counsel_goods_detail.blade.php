@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    이용권 상담 일지 상세
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <div class="row pt-2" data-div-main="counsel_goods_detail">
        <input type="hidden" data-main-student-seq value="{{ $student_seq }}">
        <input type="hidden" data-main-counsel-seq value="{{ $counsel_seq }}">
        <input type="hidden" data-main-group-type2 value="{{ $group_type2 }}">
        <input type="hidden" data-main-student-type value="{{ $counsel ? $counsel->student_type:'' }}">

        <div class="sub-title d-flex justify-content-between">
            <h2 class="text-sb-42px">
                <button data-btn-back-page class="btn p-0 row mx-0 all-center" onclick="clGoodsDetailBack();">
                    <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
                </button>
                <span class="me-2">{{ $student->student_name }} 학생</span>
                <span
                    class="scale-text-white primary-bg-mian px-3 py-1 text-sb-20px rounded-pill">{{ $student->grade_name }}</span>
            </h2>
        </div>
        <table class="w-100 table-serach-style table-border-xless table-h-92 div-shadow-style rounded-3">
            <colgroup>
                <col style="width: 33.33%;">
                <col style="width: 33.33%;">
                <col style="width: 33.33%;">
            </colgroup>
            <thead></thead>
            <tbody>
                <tr class="text-start">
                    <td class="text-start p-4 scale-text-gray_06 py-4">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative align-items-center">
                            <select data-select-top="region" {{ $group_type2 != 'general' ? 'disabled' : '' }}
                                onchange="ClGoodsSelectTop(this, 'region')"
                                class="border-none lg-select rounded-0 text-sb-20px {{ $group_type2 != 'general' ? 'scale-text-gray_05' : '' }} p-0 w-100 ">
                                @if ($group_type2 == 'general')
                                    <option value="">소속 팀을 선택해주세요.</option>
                                @endif
                                @if (!empty($regions))
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                alt="" width="32" height="32">
                        </div>
                    </td>
                    <td class="text-start px-4">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative align-items-center">
                            <select data-select-top="team" {{ $group_type2 != 'general' ? 'disabled' : '' }}
                                onchange="ClGoodsSelectTop(this, 'team')"
                                class="border-none lg-select rounded-0 text-sb-20px {{ $group_type2 != 'general' && $group_type2 != 'leader' ? 'scale-text-gray_05' : '' }} p-0 w-100">
                                @if ($group_type2 == 'general')
                                    <option value="">소속 팀을 선택해주세요.</option>
                                @else
                                    @if (!empty($team))
                                        <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                    @endif
                                @endif

                            </select>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                alt="" width="32" height="32">
                        </div>
                    </td>
                    <td class="text-start p-4 scale-text-gray_06">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative align-items-center">
                            <select data-select-top="teacher"
                                {{ $group_type2 != 'general' && $group_type2 != 'leader' ? 'disabled' : '' }}
                                onchange="ClGoodsSelectTop(this, 'teacher')"
                                class="border-none lg-select rounded-0 text-sb-20px {{ $group_type2 != 'general' && $group_type2 != 'leader' ? 'scale-text-gray_05' : '' }} p-0 w-100">
                                @if ($group_type2 == 'general' || $group_type2 == 'team')
                                    <option value="">소속 선생님을 선택해주세요.</option>
                                @else
                                    <option value="{{ session()->get('teach_seq') }}" selected>
                                        {{ session()->get('teach_name') }}</option>
                                @endif
                            </select>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                alt="" width="32" height="32">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mt-80"></div>
        {{-- 상세 설정. --}}

        <table class="w-100 table-list-style table-border-xless table-h-92" {{ $counsel_seq ? '' : 'hidden' }}>
            <colgroup>
                <col style="width: 10%;">
            </colgroup>
            <thead></thead>
            <tbody>
                <tr>
                    <input type="hidden" data-student-type value="{{ $counsel->student_type }}">
                    <input type="hidden" data-goods-end-date value="{{ $student->goods_end_date }}">

                    <td>접수구분</td>
                    <td>
                        <span data-sp-student-type
                            class="rounded basic-bg-positie scale-text-white text-sb-20px px-4 py-1"></span>
                    </td>
                    <td>접수상태</td>
                    <td>{{ $counsel->is_counsel == 'Y' ? '상담완료' : '상담대기' }}</td>
                    <td>최초 접속일시</td>
                    {{-- <td>24.02.02 17:23</td> --}}
                    {{-- $student->first_login_date --}}
                    <td>{{ str_replace('-', '.', substr($student->first_login_date, 2, 14)) }}</td>
                </tr>
                <tr>
                    <td>학습생</td>
                    <td>{{ $student->student_name }}({{ $student->grade_name }})</td>
                    <td>학부모</td>
                    <td>{{ $student->parent_name }}</td>
                    <td>학부모 연락처</td>
                    <td> {{ $student->parent_phone }} </td>
                </tr>
                <tr>
                    <td>유입구분</td>
                    <td>{{ $student->how_join }}</td>
                    <td>주소</td>
                    <td>{{ $student->address }}</td>
                    <td>상담선생님</td>
                    <td>{{ $student->counsel_teach_name }} / {{ $student->counsel_group_name }}</td>
                </tr>
                <tr>
                    <td>고객메모</td>
                    <td colspan="5" class="text-start px-4">
                        {{ $student->student_simple_memo }}
                    </td>
                </tr>
                <tr>
                    <td>관리메모</td>
                    <td colspan="5" class="text-start px-4">
                        <div class="row mx-0">
                            <div class="w-100 col px-0" {{ $counsel_detail ? 'contenteditable="true"':'' }}
                            style="max-width:1350px" data-manage-memo>
                                {{ $student->student_manage_memo }}
                            </div>
                            <div class="col-auto px-0">
                                {{-- @if ($counsel->is_counsel == 'Y') --}}
                                    <button type="button" data-btn-counsel-event="update" onclick="clGoodsInsert(this)"
                                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">수정하기</button>
                                {{-- @else
                                    <button type="button" data-btn-counsel-event="insert" onclick="clGoodsInsert(this)"
                                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">저장하기</button>
                                @endif --}}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="align-top">접수기록</td>
                    <td colspan="5" class="text-start ps-4" data-bundle="cousen_log">
                        {{-- <p>[24.02.02 17:23] [인입] 고객으로부터 상담 건이 인입됐어요.</p> --}}
                        @if(!empty($counsels_y))
                            @foreach($counsels_y as $counsel_y)
                                
                                @if(strlen($counsel_y->transfer_seq) > 0)
                                    <p>[{{ str_replace('-', '.', substr($counsel_y->transfer_reg_date, 2, 14)) }}] 
                                        {{ '<'.$counsel_y->before_teach_name.'/'.$counsel_y->before_group_name.'>에서 <'.$counsel_y->after_teach_name.'/'.$counsel_y->after_group_name.'>으로 이관 신청을접수했어요.' }}
                                        @if($counsel_y->is_move == 'Y')<span class="text-danger">[이관완료] / {{ $counsel_y->transfer_updated_at }}</span>@endif
                                        <span class="text-primary cursor-pointer" 
                                        onclick="clGoodsTransferCounselShow('{{ $counsel_y->transfer_seq }}', 
                                        '{{ $counsel_y->before_teach_name.' / '.$counsel_y->before_region_name.' / '.$counsel_y->before_group_name }}',
                                        '{{ $counsel_y->transfer_reg_date }}',
                                        '{{ $counsel_y->transfer_reason }}',
                                        '{{ $counsel_y->after_teach_name.' / '.$counsel_y->after_region_name.' / '.$counsel_y->after_group_name }}',
                                        );">이관내역확인</span>
                                    </p>
                                {{-- 상담진행 --}}
                                @elseif($counsel_y->is_counsel == 'Y')
                                    <p>
                                        [{{ str_replace('-', '.', substr($counsel_y->created_at, 2, 14)) }}] 
                                        {{ '<'.$counsel_y->counsel_teach_name.'/'.$counsel_y->group_name.'>가 상담을 진행' }}
                                        <span class="text-primary cursor-pointer" onclick="">상담내역확인</span>
                                    </p>
                                    
                                @endif
                                
                            @endforeach
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" onclick="clGoodsFirstInsert()"
            class="btn w-100 h-104 p-3 scale-text-gray_05 scale-bg-gray_01 text-sb-24px d-flex align-items-center justify-content-center mt-80 mb-80">
            <svg class="me-1" width="32" height="32" viewBox="0 0 32 32" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M24.4844 16L7.51423 16" stroke="#DCDCDC" stroke-width="3" stroke-linecap="round" />
                <path d="M16 24.4844L16 7.51423" stroke="#DCDCDC" stroke-width="3" stroke-linecap="round" />
            </svg>
            상담내용 추가입력하기
        </button>

        <div class="d-flex justify-content-between align-items-end mb-32">
            <div></div>
            <div>
                <label class="d-inline-block select-wrap select-icon h-62">
                    <select id="select2"
                        class="date-change rounded-pill ps-4 border-gray sm-select text-sb-20px me-2 h-62">
                        <option value="">기간설정</option>
                        <option value="0">1</option>
                        <option value="1">2</option>
                        <option value="2">3</option>
                    </select>
                </label>
                <label class="label-date-wrap">
                    <input type="text"
                        class="select2 smart-hb-input border-gray rounded-pill text-m-20px gray-color text-center h-62"
                        readonly="" placeholder="">
                </label>
            </div>
        </div>

        <table class="w-100 table-list-style table-border-xless table-h-92">
            <colgroup>
                <col style="width: 10%;">
            </colgroup>
            <thead></thead>
            <tbody data-bundle="counsel_list">
                <tr data-row="copy_first" hidden>
                    <td data-counsel-cnt>
                        {{-- n차 --}}
                    </td>
                    <td colspan="3" class="px-4">
                        <p class="scale-text-black text-sb-24px h-center justify-content-between">
                            <span data-counsel-title>
                                {{-- 최초 상담내역 (24.01.01 17:23 부터 18:23 까지 진행) --}}
                            </span>
                            <button class="btn all-center {{-- rotate-180 --}}" data-btn-list-open onclick="clGoodsListOpen(this)">
                                <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" width="32">
                            </button>
                        </p>
                    </td>
                </tr>
                <tr data-row="copy" data-hidden="counsel_bottom" class="" hidden>
                    <td>상담시작일시</td>
                    <td class="px-4 py-2">
                        <div class="d-flex justify-content-between">
                            <div class="text-sb-24px h-center p-3 rounded-4" data-edit="border">
                                <img src="{{ asset('images/calendar_gray_icon.svg') }}" width="32" class="me-2">
                                <div class="overflow-hidden col cursor-pointer text-start" style="height: 30px;">
                                    <div class="h-center justify-content-between">
                                        <div data-counsel-start-date="div" data-date onclick="this.closest('td').querySelector('input').showPicker()" type="text" class="text-sb-24px text-start" readonly="" placeholder="">
                                            {{-- 상담시작일시 --}}
                                            {{-- 24.01.01 17:23 --}}
                                        </div>
                                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                                    </div>
                                    <input type="datetime-local" style="width: 210px;height: 0.5px;" data-counsel-start-date="input" oninput="clGoodsDateTimeSel(this)" disabled="">
                                </div>
                            </div>
                            <p class="text-sb-24px scale-text-gray_06 h-center">상담종료일시</p>
                        </div>
                    </td>
                    <td data-hidden="counsel_bottom" class="px-4 py-2">
                        <div class="d-flex justify-content-between">
                            <div class="text-sb-24px h-center p-3 rounded-4" data-edit="border">
                                <img src="{{ asset('images/calendar_gray_icon.svg') }}" width="32" class="me-2">
                                <div class="overflow-hidden col cursor-pointer text-start" style="height: 30px;">
                                    <div class="h-center justify-content-between">
                                        <div data-counsel-end-date="div" data-date onclick="this.closest('td').querySelector('input').showPicker()" type="text" class="text-sb-24px text-start" readonly="" placeholder="">
                                            {{-- 상담시작일시 --}}
                                            {{-- 24.01.01 17:23 --}}
                                        </div>
                                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                                    </div>
                                    <input type="datetime-local" style="width: 210px;height: 0.5px;" data-counsel-end-date="input" oninput="clGoodsDateTimeSel(this)" disabled="">
                                </div>
                            </div>
                            <p class="text-sb-24px scale-text-gray_06 h-center">상담매체</p>
                        </div>
                    </td>
                    <td class="py-2 px-4">
                        <div class="p-3 rounded-4 all-center" style="width: fit-content;" data-edit="border">
                            <select data-counsel-how class="text-sb-24px border-none lg-select p-0" disabled>
                                {{-- 상담매체 --}}
                                {{-- 유선상담 --}}
                                <option value="유선상담">유선상담</option>
                            </select>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                        </div>
                    </td>
                </tr>
                <tr data-row="copy" data-hidden="counsel_bottom" class="" hidden>
                    <td>상담내용</td>
                    <td colspan="3" class="px-4">
                        <div class="d-flex justify-content-between">
                            <p data-counsel-content="row_content" style="max-width: 100%"
                            class="text-sb-24px scale-text-gray_05 w-100 text-start" ></p>
                        </div>
                    </td>
                </tr>
                <tr data-row="copy" data-hidden="counsel_bottom" class="scale-bg-gray_01" hidden>
                    <td colspan="4" class="text-end px-4">
                        
                        <button type="button" onclick="clGoodsRowsEdit(this)" data-btn-edit="update"
                            class="btn-line-xss-secondary text-sb-20px border-drak rounded text-dark scale-bg-white px-3">수정하기</button>
                        <button type="button" onclick="clGoodsRowsEdit(this)" data-btn-edit="delete"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3" hidden>상담내용 삭제하기</button>
                        <button type="button" onclick="clGoodsRowsEdit(this)" data-btn-edit="insert"
                            class="btn-line-xss-secondary text-sb-20px border-drak rounded text-dark scale-bg-white px-3" hidden>변경사항 저장하기</button>
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- 160px --}}
        <div>
            <div class="py-lg-5"></div>
            <div class="py-lg-4"></div>
            <div class="pt-lg-3"></div>
        </div>

        {{-- 모달 / 이관내역확인 --}}
              <div class="modal fade " id="cl_goods_modal_transfer" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog rounded" style="max-width: 592px;">
        <div class="modal-content border-none rounded p-3 modal-shadow-style">
          <div class="modal-header border-bottom-0">
            <h1 class="modal-title fs-5 text-b-24px" id="">
              이관내역확인
            </h1>
            <button type="button" style="width:32px;height:32px"
            class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <table class="w-100 table-list-style table-border-xless table-h-92 mt-4">

              <thead>
              </thead>
              <tbody>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">요청자명</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-before-info
                    class="text-sb-20px scale-text-black px-2"></p>
                  </td>
                </tr>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">이관일시</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-transfer-reg-date
                    class="text-sb-20px scale-text-gray_05 px-2"></p>
                  </td>
                </tr>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">이관사유</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-transfer-reason
                    class="text-sb-20px scale-text-gray_05 px-2"></p>
                  </td>
                </tr>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">피이관자</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-after-info
                    class="text-sb-20px scale-text-black px-2">박선생 / 부산광역시 / 상담선생님</p>
                  </td>
                </tr>
              </tbody>
            </table>

 
          <div class="modal-footer border-top-0 p-0 pb-2 mt-52 d-none">

          </div>
        </div>
      </div>
    </div>
    </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 접수구분
            const counsel_seq = document.querySelector('[data-main-counsel-seq]').value;
            if (counsel_seq.length > 0) {
                const student_type_el = document.querySelector('[data-sp-student-type]');
                const goods_end_date = document.querySelector('[data-goods-end-date]').value;
                const student_type = document.querySelector('[data-student-type]').value;
                ClGoodsGetType(student_type, goods_end_date, student_type_el);
            }
            clGoodsListSelect();
        });

        function clGoodsDetailBack() {
            sessionStorage.setItem('isBackNavigation', 'true');
            window.history.back();
        }

        // 신규/만료임박/만료/재등록/휴먼회원
        function ClGoodsGetType(student_type, goods_end_date, tag) {
            const today_date = new Date().format('yyyy-MM-dd');
            if (student_type == 'new') {
                if (tag) {
                    tag.innerText = '신규';
                }
                return '신규';
            } else if (student_type == 'readd') {
                //이용권 등록후 만료 1개월전
                if (goods_end_date < today_date) {
                    if (tag) tag.innerText = '만료';
                    tag.classList.add('studyColor-bg-studyComplete');
                    tag.classList.remove('basic-bg-positie');
                    return '만료';
                } else if (goods_end_date >= today_date
                    //goods_end_date에서 30일 뺀 날짜보다 오늘이 더 크면 만료임박
                    &&
                    new Date(new Date(goods_end_date).getTime() - (30 * 24 * 60 * 60 * 1000)).format('yyyy-MM-dd') <
                    today_date) {
                    if (tag) tag.innerText = '만료임박';
                    return '만료임박';
                }
                // goods_end_date 가 오늘과 차이가 1년 이상일때
                else if (new Date(goods_end_date).getTime() - new Date(today_date).getTime() > (365 * 24 * 60 * 60 *
                    1000)) {
                    if (tag) {
                        tag.innerText = '재등록';
                        tag.classList.add('scale-bg-gray_01');
                        tag.classList.add('scale-text-gray_05');
                        tag.classList.remove('basic-bg-positie');
                        tag.classList.remove('scale-text-white');

                    }
                    return '휴먼해제';
                } else {
                    if (tag) tag.innerText = '재등록';
                    return '재등록';
                }
            }
        }
        
                // 상단 select_tag(el) 선택시
                function ClGoodsSelectTop(vthis, type){
            if(type == 'region'){
                const region_seq = vthis.value;
                ClGoodsTeamSelect(region_seq);
            }
            else if(type == 'team'){
                const team_code = vthis.value;
                ClGoodsTeacherSelect(team_code);
            }
            else if(type == 'teacher'){
                const teach_seq = vthis.value;
                ClGoodsListCounselSelect(teach_seq);
            }
        }

                // 본부 선택시 팀 SELECT
                function ClGoodsTeamSelect(region_seq){
            const page = '/manage/useradd/team/select';
            const parameter = {
                region_seq: region_seq
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    const select_team = document.querySelector('[data-select-top="team"]');
                    select_team.innerHTML = '';
                    const option = document.createElement('option');
                    option.value = '';
                    option.innerText = '소속 팀을 선택해주세요.';
                    select_team.appendChild(option);
                    const teams = result.resultData;
                    teams.forEach(function(team){
                        const option = document.createElement('option');
                        option.value = team.team_code;
                        option.innerText = team.team_name;
                        select_team.appendChild(option);
                    });
                }
            }); 
        }

        // 팀 선택시 선생님 SELECT
        function ClGoodsTeacherSelect(team_code){
            const page = '/manage/userlist/teacher/select';
            const parameter = {
                serach_team: team_code
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    const select_teacher = document.querySelector('[data-select-top="teacher"]');
                    select_teacher.innerHTML = '';
                    const option = document.createElement('option');
                    option.value = '';
                    option.innerText = '소속 선생님을 선택해주세요.';
                    select_teacher.appendChild(option);
                    const teachers = result.resultData;
                    teachers.forEach(function(teacher){
                        const option = document.createElement('option');
                        option.value = teacher.id;
                        option.innerText = teacher.teach_name;
                        select_teacher.appendChild(option);
                    });
                }
            });
        }

        // 선생님 선택시 상담목록 가져오기.
        function ClGoodsListCounselSelect(teach_seq, page_num){
            // 어차피 이용권 상담은 regular가 없지만 넣어는 둠.
            const counsel_types = ['regular', 'no_regular'];
            const counsel_category = 'goods';
            const get_type = 'page';
            const stay_el = document.querySelectorAll('[data-tab-counsel-list="stay"].active');
            let is_counsel = stay_el.length > 0 ? 'N':'Y';
            let is_transfer = 'N';
            const transfer_el = document.querySelectorAll('[data-tab-counsel-list="transfer"].active');
            if(transfer_el.length > 0){
                is_transfer = 'Y';
                is_counsel = '';
            }

            const page = '/teacher/counsel/select';
            const parameter = {
                counsel_types: counsel_types,
                counsel_category: counsel_category,
                is_counsel: is_counsel,
                get_type: get_type,
                page: page_num,
                teach_seq:teach_seq,
            };

            //초기화
            const bundle = document.querySelector('[data-bundle="tby_counsel"]');
            const copy_tr = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(copy_tr);
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){

                }
            });
        }

        // 상단 상담내용 수정눌렀을떼
        function clGoodsInsertUpdateTag(is_update){
            // data-manage-memo; contenteditable="true"
            const content_el = document.querySelector('[data-manage-memo]');
            // data-btn-counsel-event="update";
            const btn_el = document.querySelector('[data-btn-counsel-event]');

            if(is_update){
                content_el.setAttribute('contenteditable', 'true');
                btn_el.setAttribute('data-btn-counsel-event', 'insert');
                btn_el.innerText = '저장하기';
            }else{
                content_el.setAttribute('contenteditable', 'false');
                btn_el.setAttribute('data-btn-counsel-event', 'update');
                btn_el.innerText = '수정하기';
            }
        }

        // 상단 상담내용 저장하기.
        function clGoodsInsert(vthis){
            if(vthis.getAttribute('data-btn-counsel-event') == 'update'){
                clGoodsInsertUpdateTag(true);
                return;
            }

            const student_seq = document.querySelector('[data-main-student-seq]').value;
            const manage_memo = document.querySelector('[data-manage-memo]').innerText;

            const msg = 
            `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">관리메모를 저장하시겠습니까?</p>
                <p class="modal-title text-center text-sb-24px alert-bottom-m studyColor-text-studyComplete" id="">수정은 저장후에도 가능합니다.</p>
            </div>
            `;
            const page = '/manage/counsel/manage/memo/update'
            const parameter = {
                student_seq: student_seq,
                manage_memo: manage_memo
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('관리메모가 저장되었습니다.');
                    clGoodsInsertUpdateTag(false);
                }
            });
        }
        function clGoodsInsertLast(parameter, msg, callback){
            
            sAlert('', msg, 3, function() {
                const page = "/manage/counsel/detail/insert";
                queryFetch(page, parameter, function(result) {
                    if ((result.resultCode || '') == 'success') {
                        let msg = '상담이 저장 되었습니다.';
                        sAlert('', `<span class="text-sb-28px">${msg}</span>`, 4);

                        // 저장하기 버튼 > 수정하기 버튼
                        if(callback != undefined){
                            callback();
                        }
                    } else {
                        
                    }
                });
            });
        }

        // 상담 목록 하단에 가져오기.
        function clGoodsListSelect(){
             // 어차피 이용권 상담은 regular가 없지만 넣어는 둠.
            const counsel_types = ['regular', 'no_regular'];
            const counsel_category = 'goods';
            const teach_seq = document.querySelector('[data-select-top="teacher"]').value;

            const page = '/teacher/counsel/select';
            const student_seq = document.querySelector('[data-main-student-seq]').value;
            const student_seqs = [`${student_seq}`];
            const parameter = {
                counsel_types: counsel_types,
                counsel_category: counsel_category,
                is_counsel: 'Y',
                teach_seq:teach_seq,
                is_order_by_detail_created_at:'Y',
                student_seqs:student_seqs
            };
            queryFetch(page, parameter, function(result){
                //초기화
                const bundle = document.querySelector('[data-bundle="counsel_list"]');
                const copy_tr = [];
                bundle.querySelectorAll('[data-row="copy_first"], [data-row="copy"]').forEach(function(tr){
                    copy_tr.push(tr.cloneNode(true));
                });
                bundle.innerHTML = '';
                copy_tr.forEach(function(tr){
                    bundle.appendChild(tr);
                });
                let is_first = false;
                result.counsels.forEach(function(counsel){
                    const vdom_tr = document.createDocumentFragment();
                    copy_tr.forEach(function(tr){
                        const copy_tr = tr.cloneNode(true);
                        vdom_tr.appendChild(copy_tr);
                    });
                    vdom_tr.querySelectorAll('[data-row]').forEach(function(tr){
                        tr.setAttribute('data-row', tr.getAttribute('data-row').replace('copy', 'clone'));
                        tr.setAttribute('data-idx', counsel.id);
                    });
                    vdom_tr.querySelector('[data-row="clone_first"]').hidden = false;
                    vdom_tr.querySelector('[data-counsel-title]').innerText = `${counsel.start_datetime} 부터 ${counsel.end_datetime} 까지 진행`;
                    const start_date_el = vdom_tr.querySelector('[data-counsel-start-date="div"]');
                    start_date_el.innerText = counsel.start_datetime.substr(2,14).replace(/-/gi, '.');
                    start_date_el.setAttribute('data-text', counsel.start_datetime.substr(2,14).replace(/-/gi, '.'));
                    vdom_tr.querySelector('[data-counsel-start-date="input"]').value = counsel.start_datetime.substr(0,16);

                    const end_date_el = vdom_tr.querySelector('[data-counsel-end-date="div"]');
                    end_date_el.innerText = counsel.end_datetime.substr(2,14).replace(/-/gi, '.');
                    end_date_el.setAttribute('data-text', counsel.end_datetime.substr(2,14).replace(/-/gi, '.'));
                    vdom_tr.querySelector('[data-counsel-end-date="input"]').value = counsel.end_datetime.substr(0,16);

                    const content_el = vdom_tr.querySelector('[data-counsel-content="row_content"]');
                    content_el.innerText = counsel.content;
                    content_el.setAttribute('data-text', counsel.content);

                    const counsel_how_el = vdom_tr.querySelector('[data-counsel-how]');
                    counsel_how_el.value = counsel.counsel_how;
                    counsel_how_el.setAttribute('data-value', counsel.counsel_how);

                    vdom_tr.querySelector('[data-counsel-cnt]').innerText = `${counsel.counsel_cnt}차`;
                    
                    // 첫 번째 열기.
                    if(!is_first){
                        is_first = true;
                        clGoodsListOpen(vdom_tr.querySelector('[data-btn-list-open]'));
                    }

                    bundle.appendChild(vdom_tr);
                    

                });
            });
        }

        // 하단 상단목록 열기.
        function clGoodsListOpen(vthis){
            const tr = vthis.closest('tr');
            if(vthis.classList.contains('rotate-180')){
                vthis.classList.remove('rotate-180');
                const tr_next1 = tr.nextElementSibling;
                const tr_next2 = tr_next1.nextElementSibling;
                const tr_next3 = tr_next2.nextElementSibling;
                tr_next1.hidden = true;
                tr_next2.hidden = true;
                tr_next3.hidden = true;
            }else{
                vthis.classList.add('rotate-180');
                const tr_next1 = tr.nextElementSibling;
                const tr_next2 = tr_next1.nextElementSibling;
                const tr_next3 = tr_next2.nextElementSibling;
                tr_next1.hidden = false;
                tr_next2.hidden = false;
                tr_next3.hidden = false;
            }
        }

        // 리스트에서 수정하기, 삭제하기, 저장하기
        function clGoodsRowsEdit(vthis){
            const type = vthis.getAttribute('data-btn-edit');
            const idx = vthis.closest('tr').getAttribute('data-idx');
            if(type == 'update'){
                if(vthis.innerText == '수정하기'){
                    clGoodsRowsEditUpdate(idx, true);
                    vthis.innerText = '취소하기';
                }else{
                    clGoodsRowsEditUpdate(idx, false);
                    vthis.innerText = '수정하기'; 
                }
            }
            else if(type == 'delete'){
                clGoodsRowsEditDelete(idx);
            }
            else if(type == 'insert'){
                clGoodsRowsEditInsert(idx);
            }
        }

        // 리스트 수정하기
        function clGoodsRowsEditUpdate(idx, is_bool){
            const bundle = document.querySelector('[data-bundle="counsel_list"]');
            const trs = bundle.querySelectorAll(`[data-idx="${idx}"]`);
            if(is_bool){
                //data-btn-edit 모두 보이게
                const btn_edits = bundle.querySelectorAll(`[data-idx="${idx}"] [data-btn-edit]`);
                btn_edits.forEach(function(btn_edit){ btn_edit.hidden = false; });

                trs.forEach(function(tr){
                    const inputs = tr.querySelectorAll('input[disabled]');
                    inputs.forEach(function(input){ input.removeAttribute('disabled'); });
                    const select = tr.querySelector('select[disabled]');
                    if(select){
                        select.removeAttribute('disabled');
                    }
                    const hidden_els = tr.querySelectorAll('[data-edit="hidden"]');
                    hidden_els.forEach(function(hidden_el){ hidden_el.hidden = false; });
                    const border_els = tr.querySelectorAll('[data-edit="border"]');
                    border_els.forEach(function(border_el){ border_el.classList.add('border-gray'); });
                    const content = tr.querySelector('[data-counsel-content="row_content"]');
                    if(content) content.setAttribute('contenteditable', 'true');
                });
            }else{
                //data-btn-edit 수정하기만 보이게.
                const btn_edits = bundle.querySelectorAll(`[data-idx="${idx}"] [data-btn-edit]`);
                btn_edits.forEach(function(btn_edit){ 
                    if(btn_edit.getAttribute('data-btn-edit') == 'update'){
                        btn_edit.hidden = false;
                    }else{
                        btn_edit.hidden = true;
                    }
                });

                trs.forEach(function(tr){
                    const inputs = tr.querySelectorAll('input');
                    inputs.forEach(function(input){ input.setAttribute('disabled', ''); });
                    const select = tr.querySelector('select');
                    if(select){
                        select.setAttribute('disabled', '');
                    }
                    const hidden_els = tr.querySelectorAll('[data-edit="hidden"]');
                    hidden_els.forEach(function(hidden_el){ hidden_el.hidden = true; });
                    const border_els = tr.querySelectorAll('[data-edit="border"]');
                    border_els.forEach(function(border_el){ border_el.classList.remove('border-gray'); });
                    const content = tr.querySelector('[data-counsel-content="row_content"]');
                    if(content) content.setAttribute('contenteditable', 'false');

                    const data_texts = tr.querySelectorAll('[data-text]');
                    data_texts.forEach(function(data_text){
                        data_text.innerText = data_text.getAttribute('data-text');
                    });
                    const data_values = tr.querySelectorAll('[data-value]');
                    data_values.forEach(function(data_value){
                        data_value.value = data_value.getAttribute('data-value');
                    });
                });
            }
        }

        // 만든날짜 선택
        function clGoodsDateTimeSel(vthis) {
            //datetime-local format yyyy.MM.dd HH:mm 변경
            const date = new Date(vthis.value);
            vthis.closest('td').querySelector('[data-date]').innerText = date.format('yy.MM.dd HH:mm')
        }

        // 하단 리스트 저장/변경하기.
        function clGoodsRowsEditInsert(idx){
            const counsel_seq = (idx == 'new' ? '': idx);
            const student_seq = document.querySelector('[data-main-student-seq]').value;
            let content = '';
            let start_datetime = '';
            let end_datetime = '';

            const bundle = document.querySelector('[data-bundle="counsel_list"]');
            const trs = bundle.querySelectorAll(`[data-idx="${idx}"]`);
            trs.forEach(function(tr){
                const content_el = tr.querySelector('[data-counsel-content="row_content"]');
                if(content_el) content = content_el.innerText;
                const start_datetime_el = tr.querySelector('[data-counsel-start-date="input"]');
                if(start_datetime_el) start_datetime = start_datetime_el.value;
                const end_datetime_el = tr.querySelector('[data-counsel-end-date="input"]');
                if(end_datetime_el) end_datetime = end_datetime_el.value;
            });
            const student_type = document.querySelector('[data-main-student-type]').value;
            const target_type = 'student';
            const counsel_type = 'no_regular';
            const counsel_how = '유선상담';

            const msg = 
            `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">이용권 상담내용을 저장하시겠습니까?</p>
                <p class="modal-title text-center text-sb-24px alert-bottom-m studyColor-text-studyComplete" id="">수정은 저장후에도 가능합니다.</p>
            </div>
            `;
            const parameter = {
                counsel_seq: counsel_seq,
                student_seq: student_seq,
                content: content,
                target_type: target_type,
                counsel_type: counsel_type,
                start_datetime: start_datetime,
                end_datetime: end_datetime,
                counsel_how:counsel_how,
                student_type:student_type
            };
            clGoodsInsertLast(parameter, msg, function(){
                clGoodsListSelect();                
            });
        }
        
        // 하단 리스트 삭제하기.
        function clGoodsRowsEditDelete(idx){
            if(idx == 'new'){
                //테이블 tag만 삭제.
                const bundle = document.querySelector('[data-bundle="counsel_list"]');
                const trs = bundle.querySelectorAll(`[data-idx="${idx}"]`);
                trs.forEach(function(tr){
                    tr.remove();
                });
                return;
            }
            const student_seq = document.querySelector('[data-main-student-seq]').value;
            const counsel_seq = idx;

            const page = "/manage/counsel/delete";
            const parameter = {
                student_seq:student_seq,
                counsel_seq:counsel_seq,
            };
            const msg = 
            `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">이용권 상담내용을 삭제하시겠습니까?</p>
                <p class="modal-title text-center text-danger text-sb-24px alert-top-m-20" id="">삭제시 복구 할 수 없습니다.</p>
            </div>
            `
            sAlert('', msg, 3, function() {
                queryFetch(page, parameter, function(result) {
                    if ((result.resultCode || '') == 'success') {
                        let msg = '상담이 삭제 되었습니다.';
                        sAlert('', `<span class="text-sb-28px">${msg}</span>`, 4);
                        clGoodsListSelect();
                    } else {
                        
                    }
                });
            });
            
            
        }

        // 상담내용 추가 입력하기 클릭시 
        function clGoodsFirstInsert(){
            const msg = 
            `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">상댐내용을 추가 하시겠습니까?</p>
            </div>
            `;
            sAlert('', msg, 3, function() {
                //초기화
                const bundle = document.querySelector('[data-bundle="counsel_list"]');
                const copy_tr = bundle.querySelector('[data-row="copy"]');
                const vdom_tr = document.createDocumentFragment();
                const copy_trs = [];
                bundle.querySelectorAll('[data-row="copy_first"], [data-row="copy"]').forEach(function(tr){
                    copy_trs.push(tr.cloneNode(true));
                });
                copy_trs.forEach(function(tr){
                    vdom_tr.appendChild(tr);
                });

                vdom_tr.querySelectorAll('[data-row]').forEach(function(tr){
                    tr.setAttribute('data-row', tr.getAttribute('data-row').replace('copy', 'clone'));
                    tr.setAttribute('data-idx', "new");
                    tr.hidden = false;
                });
                vdom_tr.querySelector('[data-counsel-cnt]').innerText = bundle.querySelectorAll('[data-row="clone_first"] [data-counsel-cnt]').length + 1 + '차';
                vdom_tr.querySelector('[data-counsel-start-date="input"]').value = new Date().format('yyyy-MM-ddTHH:mm');
                vdom_tr.querySelector('[data-counsel-end-date="input"]').value = new Date().format('yyyy-MM-ddTHH:mm');
                vdom_tr.querySelector('[data-row="clone"] [data-counsel-start-date="input"]').oninput();
                vdom_tr.querySelector('[data-row="clone"] [data-counsel-end-date="input"]').oninput();

                vdom_tr.querySelector('[data-btn-edit="delete"]').innerText = '삭제하기';
                vdom_tr.querySelector('[data-btn-edit="insert"]').innerText = '저장하기';


                


                const first_clone = bundle.querySelector('[data-row="clone_first"]'); 
                if(first_clone){
                    first_clone.before(vdom_tr);
                }else{
                    bundle.appendChild(vdom_tr);
                }
                setTimeout(() => {
                    bundle.querySelector('[data-row="clone"] [data-btn-edit="update"]').click();
                    bundle.querySelector('[data-row="clone_first"] [data-btn-list-open]').click();
                    
                    bundle.querySelector('[data-row="clone"] [data-btn-edit="update"]').remove();
                }, 1);
            });
        }

        // 이관내역확인
        function clGoodsTransferCounselShow(transfer_seq, before_info, transfer_reg_date, transfer_reason, after_info){
            const modal_el = document.getElementById('cl_goods_modal_transfer');
            modal_el.querySelector('[data-before-info]').innerText = before_info;
            //2024-02-01 08:00  > 24.02.01 08:00
            modal_el.querySelector('[data-transfer-reg-date]').innerText = transfer_reg_date.substr(2,14).replace(/-/gi, '.');
            modal_el.querySelector('[data-transfer-reason]').innerText = transfer_reason;
            modal_el.querySelector('[data-after-info]').innerText = after_info;

            const myModal = new bootstrap.Modal(document.getElementById('cl_goods_modal_transfer'), {
                keyboard: false,
                backdrop: 'static'
            });
            myModal.show();
        }
    </script>
@endsection
