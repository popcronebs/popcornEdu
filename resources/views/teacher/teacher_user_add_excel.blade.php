@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    사용자 등록(일괄)
@endsection

{{-- 컨텐트 --}}
@section('layout_coutent')
    <div class="row pt-2" data-div-main="user_add_excel">
        <input type="hidden" data-main-user-type value="{{ $user_type }}">
        <input type="hidden" data-main-region-seq value="{{ $region_seq }}">
        <input type="hidden" data-main-region-name value="{{ $region_name }}">
        <input type="hidden" data-main-team-code value="{{ $team_code }}">
        <input type="hidden" data-main-team-name value="{{ $team_name }}">
        <input type="hidden" data-main-team-type value="{{ $team_type }}">

        <input id="useradd_inp_excelfile" type="file" onchange="useraddReadExcel();" hidden value="file" accept=".xls,.xlsx" >
        <article class="sub-title d-flex justify-content-between">
            <h2 class="text-sb-42px">
                <button data-btn-back-page class="btn p-0 row mx-0 all-center" onclick="teachUserAddBack();">
                    <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
                </button>
                <span class="me-2">사용자 등록(일괄)</span>
            </h2>
        </article>
        <section class="row">
            <aside class="col-3 ps-0">
                <div class="div-shadow-style rounded-3 pt-32 pb-4 px-4">
                    <div class=" d-flex justify-content-between align-items-center">
                        <span class="text-sb-24px">선택된 파일</span>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" alt="" width="32"
                            height="32">
                    </div>
                    <div class="pt-4">
                        <div class="excel-wrap p-12 rounded-3 scale-bg-gray_01 rounded">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.7727 7.09091V16.5C15.7727 18.3082 14.3082 19.7727 12.5 19.7727C10.6918 19.7727 9.22727 18.3082 9.22727 16.5V6.27273C9.22727 5.14364 10.1436 4.22727 11.2727 4.22727C12.4018 4.22727 13.3182 5.14364 13.3182 6.27273V14.8636C13.3182 15.3136 12.95 15.6818 12.5 15.6818C12.05 15.6818 11.6818 15.3136 11.6818 14.8636V7.09091H10.4545V14.8636C10.4545 15.9927 11.3709 16.9091 12.5 16.9091C13.6291 16.9091 14.5455 15.9927 14.5455 14.8636V6.27273C14.5455 4.46455 13.0809 3 11.2727 3C9.46455 3 8 4.46455 8 6.27273V16.5C8 18.9873 10.0127 21 12.5 21C14.9873 21 17 18.9873 17 16.5V7.09091H15.7727Z"
                                    fill="#999999" stroke="#999999" stroke-width="0.4"></path>
                            </svg>
                            <span class="text-sb-20px align-middle text-primary" data-excel-file-name></span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="teachUserAddUserInsert();"
                    class="btn-line-ms-secondary justify-content-center text-sb-24px rounded border-gray scale-bg-white scale-text-white-hover primary-bg-mian-hover scale-text-gray_05 me-1 w-100 mt-32">저장하기</button>

            </aside>
            <div class="col-9 pe-0">
                <div class="d-flex justify-content-between align-items-center mb-32">
                    <div class="scale-bg-gray_01 ps-4" data-bundle="user_group_cnt">
                        <span data-row="copy" class="row rounded  py-12 text-sb-20px pe-4" hidden>
                            <span class="d-flex scale-text-gray_05 col-auto">
                                <span data-group-name>관리 선생님</span>
                                <span class="d-block border-gray mx-12 my-1"></span>
                                <b class="studyColor-text-studyComplete" data-cnt>1명</b>
                            </span>
                        </span>
                    </div>
                    <button type="button" onclick="teachUserAddPrint();"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 ">
                        <img src="{{ asset('images/print_icon.svg') }}" width="24">
                        인쇄하기
                    </button>
                </div>
                <div data-div-in-table>
                    <table class="table-style w-100" style="min-width: 100%;">
                        <colgroup>
                            <col style="width: 80px;">

                        </colgroup>
                        <thead class="">
                            <tr class="text-sb-20px modal-shadow-style rounded">
                                <th style="width: 80px">구분</th>
                                <th>그룹</th>
                                <th>소속</th>
                                <th>임시아이디</th>
                                <th>임시비밀번호</th>
                                <th>이름</th>
                                <th>휴대전화</th>
                            </tr>
                        </thead>
                        <tbody data-bundle="add_user_list">
                            <tr class="text-m-20px" data-row="copy" hidden>
                                <input type="hidden" data-region-seq>
                                <input type="hidden" data-team-code>
                                <input type="hidden" data-user-type>
                                <input type="hidden" data-group-seq>

                                <td class=" py-2" data-idx>1</td>
                                <td class=" py-2" data-group-name data-text="#관리선생님"></td>
                                <td class=" py-4">
                                    <p data-region-name data-text="#부산남부"></p>
                                    <p data-team-name data-text="#해운대구1팀"></p>
                                </td>
                                <td class=" py-2" data-user-id data-text="#a111301"></td>
                                <td class=" py-2" data-user-pw data-text="#123412"></td>
                                <td class=" py-2">
                                    <p data-user-name data-text="#이단체"></p>
                                </td>
                                <td class=" py-2">
                                    <p data-user-phone data-text="#010-1234-1234"></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-52 d-flex justify-content-between align-items-center">
                    <button type="button" onclick="document.querySelector('#useradd_inp_excelfile').click();"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 ">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10.8654 3.57031C10.2494 3.57031 9.75008 4.06966 9.75008 4.68565V9.33936L6.87265 9.33936C5.84457 9.33936 5.3355 10.5875 6.07034 11.3065L11.1712 16.2975C11.6171 16.7338 12.3299 16.7338 12.7758 16.2975L17.8767 11.3065C18.6115 10.5875 18.1024 9.33936 17.0744 9.33936L14.1665 9.33936V4.68565C14.1665 4.06966 13.6671 3.57031 13.0512 3.57031H10.8654Z"
                                fill="#DCDCDC"></path>
                            <rect x="5.57031" y="17.8203" width="12.8027" height="1.75074" rx="0.875369" fill="#DCDCDC">
                            </rect>
                        </svg>
                        Excel 불러오기
                    </button>
                    <div class="pagination">
                        {{-- <a href="#" class="prev">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.6667 22.6667L12.7098 16.7546C12.3178 16.3656 12.3154 15.7324 12.7045 15.3404L18.6667 9.33337"
                                    stroke="#DCDCDC" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round">
                                </path>
                            </svg>
                        </a>
                        <span class="page active">1</span>
                        <span class="page">2</span>
                        <span class="page">3</span>
                        <span class="page">4</span>
                        <span class="page">5</span>
                        <a href="#" class="next">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.3333 22.6667L19.2902 16.7546C19.6822 16.3656 19.6846 15.7324 19.2955 15.3404L13.3333 9.33337"
                                    stroke="#DCDCDC" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round">
                                </path>
                            </svg>
                        </a> --}}
                    </div>
                    <div class="h-center gap-2">
                        <button type="button" onclick="teachUserAddChkGroup();"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 ">
                            그룹 넣기
                        </button>
                        <button type="button" onclick="teachUserAddTempID();"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 ">
                            임시아이디 발급
                        </button>
                    </div>
                </div>
            </div>
        </section>

        {{-- 160px --}}
        <div>
            <div class="py-lg-5"></div>
            <div class="py-lg-4"></div>
            <div class="pt-lg-3"></div>
        </div>

        {{-- 모달 / 그룹 선택 --}}
        <div class="modal fade" id="teach_user_add_group_sel_modal" tabindex="-1" aria-hidden="true"
            style="display: ;">
            <div class="modal-dialog  modal-dialog-centered rounded-4 modal-lg">
                <input type="hidden" class="teach_seqs">
                <input type="hidden" class="chg_region_seq">
                <input type="hidden" class="chg_team_code">
                <div class="modal-content">
                    <div class="modal-header" hidden>
                        <h1 class="modal-title fs-5">소속/관할 변경</h1>
                    </div>
                    <div class="modal-body">
                        <p class="modal-title text-center text-b-24px " id="">빈 그룹에 들어갈 그룹은 선택해주세요.</p>
                        <div class="row mx-0 justify-content-center align-items-center h-100 flex-wrap mt-4"
                            data-bundle="teacher_group">
                            @if (!empty($groups))
                                @foreach ($groups as $group)
                                    <label class="radio row mx-0 col-5" data-row="clone">
                                        <input type="radio" name="group_select" class="">
                                        <span class="col-auto px-0"></span>
                                        <p class="text-m-20px ms-1 col-auto">
                                            {{ $group['group_type'] == 'teacher' ? '운영' : '' }}
                                            {{ $group['group_type'] == 'parent' ? '학부모' : '' }}
                                            {{ $group['group_type'] == 'student' ? '학생' : '' }}
                                            -
                                            <span data-group-name>{{ $group['group_name'] }}</span>
                                        </p>
                                        <input type="hidden" data-group-seq value="{{ $group['group_seq'] }}">
                                        <input type="hidden" data-group-type value="{{ $group['group_type'] }}">
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row w-100 p-0 m-0">
                            <div class="col-6 ps-0">
                                <button type="button" data-bs-dismiss="modal" aria-label="Close" data-modal-close="1"
                                    class="btn-lg-secondary text-sb-24px rounded scale-bg-gray_01 scale-text-gray_05 w-100 justify-content-center">닫기</button>
                            </div>
                            <div class="col-6 pe-0">
                                <button type="button" onclick="teachUserAddChkGroupInsert()"
                                    class="btn-lg-primary text-b-24px rounded scale-text-white w-100 text-center justify-content-center">
                                    <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true"
                                        hidden></span>
                                    빈 그룹 넣기
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script>
        var teams_arr = @json($temas);
        var groups_arr = @json($groups);
        let add_users = [];
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(() => {
                let fileInput = document.querySelector('#useradd_inp_excelfile');
                const msg_el = document.querySelector('[data-file-select-msg]');
                if (fileInput.value == '' && msg_el == null) {
                    const msg =
                        `
                    <div class="text-m-28px" data-file-select-msg>파일을 선택해주세요</div>
                    `;
                    sAlert('', msg, 4, function() {
                        let fileInput = document.querySelector('#useradd_inp_excelfile');
                        fileInput.value = ''; // 이전에 선택한 파일 선택 해제
                        fileInput.click();
                        setTimeout(() => {
                            const msg_el = document.querySelector('[data-file-select-msg]');
                            if (msg_el) msg_el.remove();
                        }, 2000);
                    }, null, '파일불러오기');

                }
            }, 1000);
        });
        //뒤로가로
        function teachUserAddBack() {
            sessionStorage.setItem('isBackNavigation', 'true');
            window.history.back();
        }

        // 엑셀파일 가져오기.
        function useraddReadExcel() {
            let filePath = document.querySelector('#useradd_inp_excelfile').value;
            //경로가 없으면 input 초기화 후 종료
            if (filePath == null || filePath == undefined || filePath.length < 1) {
                document.querySelector('[data-excel-file-name]').innerHTML = '';
                return;
            }
            document.querySelector('#system_alert').hidden = true;

            //파일 경로로 파일명 가져오기
            let filePathSplit = filePath.split('\\');
            let fileName = filePathSplit[filePathSplit.length - 1];
            document.querySelector('[data-excel-file-name]').innerHTML = fileName;

            //소속 그룹별로 카운트 넣는 배열
            let groupArr = {};

            //엑셀에서 파일 읽는 부분
            let input = event.target;
            let reader = new FileReader();
            reader.onload = function() {
                let data = reader.result;
                let workBook = XLSX.read(data, {
                    type: 'binary'
                });
                var sheet1 = workBook.SheetNames[0];
                let rows = XLSX.utils.sheet_to_json(workBook.Sheets[sheet1]);
                add_users = rows;
                teachUserAddTableSetting()
            };
            reader.readAsBinaryString(input.files[0]);
        }

        //
        function teachUserAddTableSetting() {
            if (add_users.length < 1) {
                // 사용자 정보가 엑셀에 없을때.
                teachUserAddNoneUserInExcel();
                return;
            }
            const bundle = document.querySelector('[data-bundle="add_user_list"]');
            const copy_row = bundle.querySelector('[data-row="copy"]');
            bundle.innerHTML = '';
            bundle.appendChild(copy_row);

            const main_region_seq = document.querySelector('[data-main-region-seq]').value;
            const main_region_name = document.querySelector('[data-main-region-name]').value;
            const main_team_code = document.querySelector('[data-main-team-code]').value;
            const main_team_name = document.querySelector('[data-main-team-name]').value;

            let idx_cnt = 0;
            add_users.forEach(user => {
                //아이디, 비밀번호, 이름, 휴대폰번호, 소속, 팀, 사용자그룹
                idx_cnt++;
                let is_group = false;
                let is_region = false;
                let is_team = false;
                const row = copy_row.cloneNode(true);
                row.setAttribute('data-row', 'clone');
                row.querySelector('[data-idx]').innerHTML = idx_cnt;
                row.querySelector('[data-user-id]').innerHTML = ''; //user['아이디']??'';
                row.querySelector('[data-user-pw]').innerHTML = ''; //user['비밀번호']??'';
                row.querySelector('[data-user-name]').innerHTML = user['이름'];
                row.querySelector('[data-user-phone]').innerHTML = user['휴대폰번호'];

                const group_name = user['사용자그룹'];
                if (groups_arr && group_name && groups_arr[group_name]) {
                    row.querySelector('[data-group-seq]').value = groups_arr[group_name].group_seq;
                    row.querySelector('[data-group-name]').innerHTML = group_name;
                    row.querySelector('[data-user-type]').value = groups_arr[group_name].group_type;
                    is_group = true;
                } else {
                    row.querySelector('[data-group-name]').innerHTML = '';
                    row.querySelector('[data-user-type]').value = '';
                }


                const team_name = user['팀'];
                const region_name = user['소속'];
                // 이전화면에서 넘어온 소속, 팀 정보가 있을때.
                if (main_region_seq) {
                    row.querySelector('[data-region-seq]').value = main_region_seq;
                    row.querySelector('[data-region-name]').innerHTML = main_region_name;
                    is_region = true;
                }
                if (main_team_code) {
                    row.querySelector('[data-team-code]').value = main_team_code;
                    row.querySelector('[data-team-name]').innerHTML = main_team_name;
                    is_team = true;
                } else {
                    //이전 화면에서 넘어온 팀이 없을때는 엑셀에 있는 팀 정보로 셋팅.
                    if (teams_arr && team_name && teams_arr[team_name]) {
                        if (teams_arr[team_name].region_name == region_name) {
                            row.querySelector('[data-region-name]').innerHTML = region_name;
                            row.querySelector('[data-team-name]').innerHTML = team_name;
                            row.querySelector('[data-region-seq]').value = teams_arr[team_name].region_seq;
                            row.querySelector('[data-team-code]').value = teams_arr[team_name].team_code;
                            is_region = true;
                            is_team = true;
                        } else {
                            if (!is_region) row.querySelector('[data-region-name]').innerHTML = '';
                            row.querySelector('[data-team-name]').innerHTML = '미배정';
                        }
                    } else {
                        if (!is_region) row.querySelector('[data-region-name]').innerHTML = '';
                        row.querySelector('[data-team-name]').innerHTML = '미배정';
                    }
                }

                if (is_group) {
                    row.querySelector('[data-group-name]').classList.add('active');
                    row.querySelector('[data-group-name]').classList.add('text-success');
                }
                if (is_region) {
                    row.querySelector('[data-region-name]').classList.add('active');
                    row.querySelector('[data-region-name]').classList.add('text-success');
                }
                if (is_team) {
                    row.querySelector('[data-team-name]').classList.add('active');
                    row.querySelector('[data-team-name]').classList.add('text-success');
                }
                row.hidden = false;
                bundle.appendChild(row);
            });
            teachUserAddChkGroupCnt();
        }

        // 사용자 정보가 엑셀에 없을때.
        function teachUserAddNoneUserInExcel() {
            const msg =
                `
                    <div class="text-m-28px" data-file-select-msg>사용자 정보가 없습니다</div>
                    <div class="text-m-20px text-danger">다시 파일을 선택해주세요</div>
                `;
            sAlert('', msg, 4, function() {
                let fileInput = document.querySelector('#useradd_inp_excelfile');
                fileInput.value = ''; // 이전에 선택한 파일 선택 해제
                fileInput.click();
                setTimeout(() => {
                    const msg_el = document.querySelector('[data-file-select-msg]');
                    if (msg_el) msg_el.remove();
                }, 2000);
            }, null, '파일불러오기');
        }

        //임시아이디 발급
        function teachUserAddTempID() {
            let sel_tr = document.querySelectorAll('[data-bundle="add_user_list"] tr:not([data-row="copy"]):not([hidden])');
            if (sel_tr == null || sel_tr == undefined || sel_tr.length < 1) {
                toast('임시 아이디를 발급할 리스트가 없습니다.');
                return;
            }
            let none_id_cnt = 0;
            //아이디 없는 학생수 가져오기.
            document.querySelectorAll('[data-row="clone"] [data-user-id]').forEach(function(item) {
                if (item.innerHTML.length < 1) {
                    none_id_cnt++;
                }
            });
            if(none_id_cnt == 0){
                toast('임시 아이디를 발급되어 있습니다.');
                return;
            }

            const file_name = document.querySelector('[data-excel-file-name]').innerHTML;
            const msg =
            `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
            <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">등록된 아이디가 없습니다.</p>
            <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">임시아이디를 발급하시겠습니까?</p>
            <div class="d-flex justify-content-between align-items-center scale-bg-gray_01 rounded px-4 h-68 w-100 mt-32">
                <p class="text-m-20px scale-text-gray_05">${file_name}</p>
                <p class="text-m-20px scale-text-black">${none_id_cnt}명</p>
            </div>
            </div>
            `;
            sAlert('', msg, 3, function() {
                let dTime = '';

                let idx = 0;
                sel_tr.forEach(function(item) {
                    idx++;
                    dTime = new Date();
                    let req_time = dTime.getTime();
                    req_time = (req_time.toString()).substr(-9);
                    //랜덤값 무조건 4자리
                    req_time += Math.floor(Math.random() * 10000).toString().padStart(4, "0");
                    let tempID = 'temP' + req_time.toString() + idx.toString().padStart(4, "0");

                    if (item.querySelector('[data-user-id]').innerHTML.length < 1) {
                        item.querySelector('[data-user-id]').innerHTML = tempID;
                        item.querySelector('[data-user-pw]').innerHTML = '1234';
                    }
                });
            }, null, '발급하기', '취소');
        }
        // 그룹넣기 - 모달 열기
        function teachUserAddChkGroup() {
            //teach_user_add_group_sel_modal
            const myModal = new bootstrap.Modal(document.getElementById('teach_user_add_group_sel_modal'), {
                keyboard: false,
                backdrop: 'static'
            });
            myModal.show();
        }
        // 그룹넣기
        function teachUserAddChkGroupInsert() {
            let sel_tr = document.querySelectorAll('[data-bundle="add_user_list"] tr:not([data-row="copy"]):not([hidden])');
            if (sel_tr == null || sel_tr == undefined || sel_tr.length < 1) {
                toast('그룹을 넣을 리스트가 없습니다.');
                return;
            }

            //input readio name group_select checked
            const radio_chk = document.querySelectorAll('[data-bundle="teacher_group"] input[type="radio"]:checked');
            if (radio_chk.length < 1) {
                toast('그룹을 선택해주세요.');
                return;
            }
            const input_group_seq = radio_chk[0].closest('[data-row="clone"]').querySelector('[data-group-seq]').value;
            const input_group_name = radio_chk[0].closest('[data-row="clone"]').querySelector('[data-group-name]')
                .innerHTML;
            const input_group_type = radio_chk[0].closest('[data-row="clone"]').querySelector('[data-group-type]').value;

            sel_tr.forEach(function(item) {
                if (item.querySelector('[data-group-seq]').innerHTML.length < 1) {
                    item.querySelector('[data-group-seq]').value = input_group_seq;
                    item.querySelector('[data-group-name]').innerHTML = input_group_name;
                    item.querySelector('[data-user-type]').value = input_group_type;
                    item.querySelector('[data-group-name]').classList.add('active');
                    item.querySelector('[data-group-name]').classList.add('text-success');
                }
            });
            const modal_el = document.getElementById('teach_user_add_group_sel_modal');
            modal_el.querySelector('[data-modal-close="1"]').click();
            teachUserAddChkGroupCnt();
        }

        // 그룹별 카운트
        function teachUserAddChkGroupCnt() {
            const tr = document.querySelectorAll('[data-bundle="add_user_list"] [data-row="clone"]');
            const group_cnt = {};
            const main_user_type = document.querySelector('[data-main-user-type]').value;
            const main_team_type = document.querySelector('[data-main-team-type]').value;
            tr.forEach(function(item) {
                let group_name = item.querySelector('[data-group-name]').innerHTML;
                if(!group_name){
                   switch(main_user_type){
                       case 'student':
                        group_name = (main_team_type  == 'after_school' ?  '방과후 학생':'학생')
                        break;
                    }
                }
                if (group_name.length > 0) {
                    if (group_cnt[group_name]) {
                        group_cnt[group_name]++;
                    } else {
                        group_cnt[group_name] = 1;
                    }
                }
            });

            const bundle = document.querySelector('[data-bundle="user_group_cnt"]');
            const copy_row = bundle.querySelector('[data-row="copy"]');
            bundle.innerHTML = '';
            bundle.appendChild(copy_row);

            for (const key in group_cnt) {
                const row = copy_row.cloneNode(true);
                row.setAttribute('data-row', 'clone');
                row.querySelector('[data-group-name]').innerHTML = key;
                row.querySelector('[data-cnt]').innerHTML = group_cnt[key] + '명';
                row.hidden = false;
                bundle.appendChild(row);
            }
        }

        //인쇄
        function teachUserAddPrint() {
            var prtContent = document.querySelector('[data-div-in-table]').cloneNode(true);
            var win = window.open();
            win.document.open();
            win.document.write('<html><head><title></title></head>');
            win.document.write('<body><style>');
            win.document.write('body, td {font-falmily: Verdana; font-size: 10pt;} tr, td, th {border:1px solid black;}');
            win.document.write(`
            table {
                border-collapse: separate;
	            border-spacing: 0px;
            }
            `);
            win.document.write('</style>');
            win.document.write(prtContent.innerHTML);
            win.document.write('</body></html>');
            win.document.close();
            win.print();
            win.close();
        }

        // 저장하기
        function teachUserAddUserInsert() {
            // tr이 존재하지 않을때
            let sel_tr = document.querySelectorAll('[data-bundle="add_user_list"] [data-row="clone"]');
            if (sel_tr == null || sel_tr == undefined || sel_tr.length < 1) {
                toast('저장할 리스트가 없습니다.');
                return;
            }

            const user_list = [];
            let is_fail = false;
            let fail_msg = '';
            sel_tr.forEach(function(item) {
                const user = {};
                user.region_seq = item.querySelector('[data-region-seq]').value;
                user.team_code = item.querySelector('[data-team-code]').value;
                user.user_type = item.querySelector('[data-user-type]').value;
                user.group_seq = item.querySelector('[data-group-seq]').value;
                user.user_id = item.querySelector('[data-user-id]').innerHTML;
                user.user_pw = item.querySelector('[data-user-pw]').innerHTML;
                user.user_name = item.querySelector('[data-user-name]').innerHTML;
                user.user_phone = item.querySelector('[data-user-phone]').innerHTML;
                user_list.push(user);

                //region_seq, user_type, group_seq, user_id, user_pw 가 없을때
                if (user.region_seq.length < 1) {
                    is_fail = true;
                    fail_msg = '소속이 없는 사용자가 있습니다.';
                }
                if (user.user_type.length < 1 || user.group_seq.length < 1) {
                    is_fail = true;
                    fail_msg = '그룹이 없는 사용자가 있습니다. 일괄 적용을 원하시면, 그룹넣기 버튼을 눌러주세요.';
                }
                if (user.user_id.length < 1 || user.user_pw.length < 1) {
                    is_fail = true;
                    fail_msg = '아이디 또는 비밀번호가 없는 사용자가 있습니다. 임시아이디 발급을 하시려면 임시아이디 발급 버튼을 눌러주세요.';
                }
            });

            if (is_fail) {
                toast(fail_msg);
                return;
            }

            const page = "/teacher/users/add/excel/insert";
            const parameter = {
                users: user_list
            };
            const msg =
            `<div class="text-m-28px">사용자 등록을 진행하시겠습니까?</div>
            `;
            sAlert('', msg, 3, function(){
                queryFetch(page, parameter, function(result) {
                    if ((result.resultCode || '') == 'success') {
                        const cnt_ap_student = result.cnt_already_phone_student;
                        const cnt_ap_parent = result.cnt_already_phone_parent;
                        const cnt_ap_teacher = result.cnt_already_phone_teacher;
                        const arr_phone = result.arr_phone;

                        const msg =
                            `<div class="text-m-28px mb-3">사용자 등록이 완료되었습니다.</div>
                            ${(arr_phone.length > 0 ? `<div class="text-m-20px text-primary">총 ${user_list.length}개 중 ${arr_phone.length}개 의 중복 전화번호가 있습니다.</div>` : '')}
                            ${(cnt_ap_student > 0 ? '<div class="text-m-20px">학생 휴대전화번호 중복 : ' + cnt_ap_student + '건</div>' : '')}
                            ${(cnt_ap_parent > 0 ? '<div class="text-m-20px">학부모 휴대전화번호 중복 : ' + cnt_ap_parent + '건</div>' : '')}
                            ${(cnt_ap_teacher > 0 ? '<div class="text-m-20px">선생님 휴대전화번호 중복 : ' + cnt_ap_teacher + '건</div>' : '')}
                            ${(arr_phone.length > 0 ? '<div class="text-m-20px">중복 휴대전화번호 : ' + arr_phone.join(', ') + '</div>' : '' )}
                            ${(arr_phone.length > 0 ? '<div class="text-m-20px">위 사용자는 등록되지 않았습니다.</div>' : '' )}
                            `;
                        sAlert('', msg, 4, function() {
                            teachUserAddBack();
                        }, null, '사용자등록완료');
                    }

                });
            });
        }
    </script>
@endsection
