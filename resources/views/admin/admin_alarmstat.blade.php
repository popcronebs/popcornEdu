@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title', '발송통계')

{{-- 네브바 체크 --}}
@section('alarmstat', 'active')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <div class="col-12 pe-3 ps-3 position-relative">
        <select id="adminstat_sel_teachers" hidden>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher['id']}}" data="{{ $teacher['teach_id'] }}">{{ $teacher['teach_name'] }}</option>
            @endforeach
        </select>
        
        {{-- select tag / SMS / 알림톡 / Push --}}
        <ul id="alarmstat_ul_tab" class="nav nav-tabs cursor-pointer">
            <li class="nav-item">
                <a class="nav-link active" onclick="alarmStatTab(this);" type="kakao">알림톡</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" onclick="alarmStatTab(this);" type="sms">SMS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" onclick="alarmStatTab(this);" type="push">Push</a>
            </li>
        </ul>

        {{-- 상단 검색 조건 --}}
        <div class="d-flex justify-content-between px-3">
            <div class="row gap-3">
                {{-- select tag / 발송상태 / 전송실패 / 전송성공 --}}
                <select id="alamstat_sel_status_code" class="form-select mt-3 mb-3 col-1" style="width:250px;">
                    <option value="">발송상태</option>
                    <option value="success">전송성공</option>
                    <option value="stay">전송대기</option>
                    <option value="rev_stay">예약대기</option>
                </select>
            </div>

            {{-- 날짜1 ~ 날짜2 / 검색 --}}
            <div class="d-flex mb-3 mt-3 gap-3">
                <div class="d-flex gap-2">
                    <input id="alamstat_inp_start_date" type="date" class="form-control" style="width:250px;" value="{{ now()->subDays(7)->format('Y-m-d') }}">
                    <input id="alamstat_inp_end_date" type="date" class="form-control" style="width:250px;" value="{{ now()->format('Y-m-d') }}"
                    >
                </div>
                <button id="alarmstat_btn_search" type="button" class="btn btn-outline-secondary px-4" onclick="alamStatSelect();">
                    <span class="sp_loading spinner-border spinner-border-sm" role="status" aria-hidden="true" hidden></span>
                    검색
                </button>
            </div>
        </div>

        <div class="m-4 overflow-auto border-bottom border-top" style="height: calc(100vh - 260px)">
            {{-- 발송 통계 테이블 --}}
            <table class="tableFixedHead table table-bordered text-center mb-0" 
            style="border-collapse:collapse">
                <thead class="table-light">
                    <tr>
                        <th scope="col">구분</th>
                        <th scope="col">발송일자</th>
                        <th scope="col">발송자</th>
                        <th scope="col">발송 수</th>
                        <th scope="col">실패 수</th>
                        <th scope="col">상태</th>
                    </tr>
                </thead>
                <tbody id="alamstat_tby_stat">
                    <tr class="copy_tr_stat" hidden>
                        <td class="alarm_type" data="구분">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="send_date" data="발송일자">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="teach_name" data="발송자">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="receiver_cnt" data="발송수">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="fail_cnt" data="실패수">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="result_code" data="상태">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="alarmstat_div_stat_str_none" class="text-center p-3" hidden>
                <span>목록이 없습니다.</span>
            </div>

        </div>
    </div>
    <script>
        alamStatSelect();
        // 
        function alarmStatTab(vthis){
            const alarmstat_ul_tab = document.querySelector('#alarmstat_ul_tab');
            alarmstat_ul_tab.querySelectorAll('a').forEach(function(el) {
                el.classList.remove('active');
            });
            vthis.classList.add('active');

            // 목록 없는 div 숨기기
            document.querySelector('#alarmstat_div_stat_str_none').hidden = true;

            // 목록 가져오기
            alamStatSelect();
        }
        // 발송통계 검색
        function alamStatSelect() {
            // 검색 조건
            const alarm_type = document.querySelector('#alarmstat_ul_tab .active').getAttribute('type');
            const status_code = document.querySelector('#alamstat_sel_status_code').value;
            let start_date = document.querySelector('#alamstat_inp_start_date').value;
            let end_date = document.querySelector('#alamstat_inp_end_date').value;
            //-와 : 그리고 공백을 제외.
            start_date = start_date.replace(/[-: ]/g,'');
            end_date = end_date.replace(/[-: ]/g,'');

            const page = "/manage/send/"+alarm_type+"/statistics";
            const parameter = {
                status_code: status_code,
                start_date: start_date,
                end_date: end_date,
            };

            //로딩
            if(document.querySelectorAll('.tr_stat').length == 0){
                document.querySelector('.copy_tr_stat').hidden = false;
            }
            const sp_loading = document.querySelector('#alarmstat_btn_search .sp_loading');
            sp_loading.hidden = false;
            queryFetch(page, parameter, function(result){
                //로딩
                sp_loading.hidden = true;

                //초기화
                const alarmstat_tby_stat = document.querySelector('#alamstat_tby_stat');
                const alarmstat_div_stat_str_none = document.querySelector('#alarmstat_div_stat_str_none');
                const copy_tr_stat = document.querySelector('.copy_tr_stat').cloneNode(true);
                alarmstat_tby_stat.innerHTML = '';
                alarmstat_tby_stat.appendChild(copy_tr_stat);
                copy_tr_stat.hidden = true;

                const alarmstat_ul_tab = document.querySelector('#alarmstat_ul_tab');
                const alarmstat_ul_tab_active = alarmstat_ul_tab.querySelector('.active');
                const alarm_type = alarmstat_ul_tab_active.getAttribute('type');

                if((result.resultCode||'') == 'success'){
                    if(result.statistics.length > 0){
                        alarmstat_div_stat_str_none.hidden = true;
                        for(let i=0; i<result.statistics.length; i++){
                            const stat = result.statistics[i];
                            const tr = copy_tr_stat.cloneNode(true);
                            tr.querySelectorAll('.loding_place').forEach(function(el){
                                el.remove();
                            });
                            tr.classList.remove('copy_tr_stat');
                            tr.classList.add('tr_stat');
                            tr.hidden = false;
                            tr.querySelector('.alarm_type').innerHTML = alamStatGetAlarmType(alarm_type, stat.sms_type||'');
                            tr.querySelector('.send_date').innerHTML = stat.send_date;
                            tr.querySelector('.teach_name').innerHTML = alamStatGetTeacherName(stat.teach_seq);
                            tr.querySelector('.receiver_cnt').innerHTML = stat.receiver_cnt;

                            tr.querySelector('.fail_cnt').innerHTML = stat.receiver_cnt*1 - stat.succ_count*1;
                            tr.querySelector('.result_code').innerHTML = stat.result_code;
                            alamStatSetColorResultCode(tr.querySelector('.result_code'), stat.result_code);

                            alarmstat_tby_stat.appendChild(tr);
                        }
                    }
                }
                // 목록 없을때 div 보이기
                if(document.querySelectorAll('.tr_stat').length == 0){
                    alarmstat_div_stat_str_none.hidden = false;
                }
            });
        }

        // 발송자 이름 가져오기
        function alamStatGetTeacherName(teach_seq){
            //옆에는 teach_id
            const adminstat_sel_teachers = document.querySelector('#adminstat_sel_teachers');
            const options = adminstat_sel_teachers.querySelectorAll('option');
            for(let i=0; i<options.length; i++){
                const option = options[i];
                if(option.value == teach_seq){
                    return option.innerHTML + '('+option.getAttribute('data')+')';
                }
            }
            return '';
        }

        // 상태에 따른 색상 변경
        function alamStatSetColorResultCode(el, result_code){
            // 발송상태 = 전송성공, 전송실패, 예약대기, 전송대기 
            if(result_code == '전송성공'){
                el.classList.add('text-success');
            }else if(result_code == '전송실패'){
                el.classList.add('text-danger');
            }else if(result_code == '예약대기'){
                el.classList.add('text-warning');
            }else if(result_code == '전송대기'){
                el.classList.add('text-warning');
            }
        }

        //
        function alamStatGetAlarmType(alarm_type,sms_type){
            if(alarm_type == 'kakao'){
                return '알림톡';
            }else if(alarm_type == 'sms'){
                if(sms_type == 'sms'){
                    return '문자(단문)';
                }else if(sms_type == 'lms'){
                    return '문자(장문)';
                }else if(sms_type == 'mms'){
                    return '문자(이미지)';
                }
            }else if(alarm_type == 'push'){
                return 'Push';
            }
        }
    </script>
@endsection
