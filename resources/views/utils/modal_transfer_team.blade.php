{{-- utilsModalTransferSave(callback) --}}

{{-- 담당 선생님 배정 변경, 상담 선생님 배정 변경, 팀 배정변경 --}}
<div class="modal fade" id="modal_transfer_team" tabindex="-1" aria-labelledby="exampleModalLabel"  aria-modal="true" role="dialog">
    <input type="hidden" data-modal-teach-seq value="">
    <input type="hidden" data-modal-team-code value="">
    <input type="hidden" data-modal-student-seq value="">
    <input type="hidden" data-modal-change-type value="">
    <input type="hidden" data-modal-charge-teach-seq value="">
    <div class="modal-dialog rounded" style="max-width: 650px;">
        <div class="modal-content border-none rounded p-3 modal-shadow-style">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5 text-b-24px h-center" id="">
                    <img src="{{ asset('images/yellow_human_icon.svg') }}" width="32">
                    <span data-modal-title data-explain="# 관리 선생님 배정 변경,상담 선생님 배정 변경, 팀 배정변경 "></span>
                </h1>
                <button type="button" style="width: 35px; height: 35px;"
                    class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-sb-20px mb-3">상담정보</p>
                <table class="w-100 table-list-style table-border-xless table-h-92 mb-52">
                    <thead>
                    </thead>
                    <tbody>
                        <tr class="">
                            <td class="text-start p-0 h-80">
                                <p class="text-sb-20px px-4">회원 정보</p>
                            </td>
                            <td class="text-start h-80 p-0">
                                <p class="text-sb-20px scale-text-black px-4 d-flex">
                                    <span class="rounded-pill basic-bg-positie text-sb-16px ps-12 pe-12 py-1 scale-text-white me-1" data-student-type data-explain="신규"></span>
                                    <span data-student-name data-explain="#김팝콘"></span>
                                    <span data-grade-name data-explain="#초3"></span>
                                    /
                                    <span data-goods-name data-explain="#초등베이직"></span>
                                    <span data-modal-goods-period data-explain="#6개월" ></span>
                            </td>
                        </tr>
                        <tr class="">
                            <td class="text-start p-0 h-80">
                                <p class="text-sb-20px px-4">상담 일정</p>
                            </td>
                            <td class="text-start h-80 p-0">
                                <p class="text-sb-20px px-4">
                                    <span data-counsel-start-date data-explain="#2024.02.01"></span>
                                    <span data-counsel-start-time data-explain="#08:00"></span>
                                    <span data-is-counsel data-explain="#상담대기"></span>
                                    <input type="hidden" data-modal-counsel-seq value="" data-explain="상담 대기면 이관할때 같이 넘김.">
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="w-100 table-list-style table-border-xless table-h-92 mb-52">
                    <colgroup>

                    </colgroup>
                    <thead>
                    </thead>
                    <tbody>
                        <tr class="">
                            <td class="text-start p-0 h-80">
                                <p class="text-sb-20px px-4">배정 정보</p>
                            </td>
                            <td class="text-start h-80 p-0">
                                <p class="text-sb-20px scale-text-black px-4">
                                    <span data-teach-name data-explain="#박선생"></span> /
                                    <span data-region-name data-explain="#부산남부"></span>
                                    <span data-team-name data-explain="#해운대1팀"></span> /
                                    <span data-teach-group-name data-explain="#상담선생님"></span>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>


                <!-- 스크립트로 처리. 선생님 시퀀스 받아서 처리. -->
                <p class="text-sb-20px mb-3">배정할 소속을 선택해주세요.</p>
                <div class="d-inline-block select-wrap select-icon w-100 mb-4">
                    <select data-modal-regions onchange="utilsModalTeamSelect()"
                        class="border-gray sm-select text-sb-20px w-100 h-62 ps-4">
                        <option value="">텍스트가 들어갈 영역입니다.</option>
                    </select>
                </div>
                <p class="text-sb-20px mb-3">배정할 소속 팀을 선택해주세요.</p>
                <div class="d-inline-block select-wrap select-icon w-100 mb-4">
                    <select data-modal-teams onchange="utilsModalTeacherSelects()"
                        class="border-gray sm-select text-sb-20px w-100 h-62 ps-4">
                        <option value="">텍스트가 들어갈 영역입니다.</option>
                    </select>
                </div>
                <p class="text-sb-20px mb-3">배정할 선생님을 선택해주세요.</p>
                <div class="d-inline-block select-wrap select-icon w-100 mb-52">
                    <select data-modal-teachers onchange="utilsModalTeacherSelects()"
                        class="border-gray sm-select text-sb-20px w-100 h-62 ps-4">
                        <option value="">배정할 선생님을 선택해주세요.</option>
                    </select>
                </div>
                <div class="h-62 px-4 px-3 border-gray rounded d-flex align-items-center">
                    <label class="checkbox me-2">
                        <input type="checkbox" class="" data-modal-chk-is-transfer-agree>
                        <span class="rounded-pill"></span>
                      </label>
                    <p class="text-sb-20px">배정 변경 절차에 관한 동의 <b class="studyColor-text-studyComplete">(필수)</b></p>
                </div>
                <p class="text-center scale-text-gray_05 text-m-20px mt-12">※ 배정 변경은 즉시 실행되기 때문에 취소할 수 없습니다.</p>
            </div>

            <div class="modal-footer border-top-0 p-0 pb-2 mt-52">
                <div class="row w-100 ">

                    <div class="col-12 ">
                        <button type="button" onclick="utilsModalTransferSave()"
                            class="btn-lg-primary text-sb-24px rounded scale-text-white w-100 text-center justify-content-center">배정 변경하기</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// 스크립트 시작.
// 우선은 한명만 되도록.(디자인이 한명밖에 없음.)
function utilsModalTransferTeam(data){
    const modal_el = document.querySelector('#modal_transfer_team');
    const modal_type = data?.modal_type||"";
    const modal_title_el = modal_el.querySelector('[data-modal-title]');
    const chage_type_el = modal_el.querySelector('[data-modal-change-type]').value;
    modal_el.querySelector('[data-modal-teach-seq]').value = data?.teach_seq||"";
    modal_el.querySelector('[data-modal-team-code]').value = data?.team_code||"";
    modal_el.querySelector('[data-student-name]').innerText = data?.student_names[0]||"";
    modal_el.querySelector('[data-modal-student-seq]').value = data?.student_seqs[0]||"";

    // student_name 가져온다.

    // let is_return = false;
    //
    const chagne_type = modal_el.querySelector('[data-modal-change-type]');
    chagne_type.value = modal_type;
    switch(modal_type){
        case 'teacher':
            modal_title_el.innerText = '';
            break;
        case 'team':
            modal_title_el.innerText = '팀 배정 변경';
            chage_type_el.value = 'team';
            break;
        default:
            toast('error', '잘못된 접근입니다.');
            return;
    }
    utilsModalStudentSelect()
    utilsModalLastCounselSelect()
    utilsModalRegionSelect();
    const myModal = new bootstrap.Modal(document.getElementById('modal_transfer_team'), {});
    myModal.show();
}

// 학생 상세 정보 불러오기.
function utilsModalStudentSelect(){
    const modal_el = document.querySelector('#modal_transfer_team');
    const chage_type = modal_el.querySelector('[data-modal-change-type]').value;
    const chage_teach_seq = modal_el.querySelector('[data-modal-teach-seq]').value;
    const student_seq = modal_el.querySelector('[data-modal-student-seq]').value;
    const modal_title_el = modal_el.querySelector('[data-modal-title]');

    const page = "/manage/userlist/student/select";
    const parameter = {
        id:student_seq
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||"") == "success"){
            const student = result.resultData[0]||{};
            if(student){
                utilsModalStudentType(student);
                modal_el.querySelector('[data-student-name]').innerText = student.student_name;
                modal_el.querySelector('[data-grade-name]').innerText = `(${student.grade_name})`;
                if(student.goods_name)
                    modal_el.querySelector('[data-goods-name]').innerText = student.goods_name;
                if(student.goods_period)
                    modal_el.querySelector('[data-modal-goods-period]').innerText = `(${student.goods_period}개월)`;

                let teach_seq = '';
                const chage_teach_seq = student.teach_seq||"";
                const counsel_teach_seq = student.counsel_teach_seq||"";
                let teach_type = '';
                // 이용권(goods) 가 없거나, goods_end_date가 오늘보다 작아서 만료되었을때는 삼담선생님 변경.
                // 아니면 담당선생님 변경.
                if(chage_type == ''){
                    if(counsel_teach_seq == teach_seq){
                        teach_type = 'counsel'
                        teach_seq = counsel_teach_seq;
                        modal_title_el.innerText = `상담 선생님 배정 변경`;
                    }else{
                        teach_type = 'teacher'
                        teach_seq = chage_teach_seq;
                        modal_title_el.innerText = `담당 선생님 배정 변경`;
                    }
                }

                chage_type.vlaue = teach_type;
                chage_teach_seq.value = teach_seq;
                utilsModalTeacherSelect(teach_type, teach_seq);
            }
        }else{}
    });

}
// 소속(region) 불러오기.
function utilsModalRegionSelect(){
    const modal_el = document.querySelector('#modal_transfer_team');
    const team_code = modal_el.querySelector('[data-modal-team-code]').value;
    const teach_seq = modal_el.querySelector('[data-modal-teach-seq]').value;

    const page = "/manage/systemteam/region/select";
    const parameter = {
        team_code: team_code,
        teach_seq: teach_seq,
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||"") == "success"){
            // regions
            const regions = result.resultData||[];
            if(regions && regions.length > 0){
                if(regions.length > 1){
                    modal_el.querySelector('[data-modal-regions]').innerHTML = '<option value="">소속을 선택해주세요.</option>';
                }else{
                    modal_el.querySelector('[data-modal-regions]').innerHTML = '';
                }
            }
            regions.forEach(function(region){
                const option = document.createElement('option');
                option.value = region.id;
                option.innerText = region.region_name;
                modal_el.querySelector('[data-modal-regions]').appendChild(option);
            });
        }else{}
    });
}

// 소속 선택시 맞는 팀 불러오기.
function utilsModalTeamSelect() {
    const modal_el = document.querySelector('#modal_transfer_team');
    const region_seq = modal_el.querySelector('[data-modal-regions]').value;
    const page = '/manage/useradd/team/select';
    const parameter = {
        region_seq: region_seq
    };
    queryFetch(page, parameter, function(result) {
        if ((result.resultCode || '') == 'success') {
            let select_team = document.querySelector('[data-modal-teams]');
            const teams = result.resultData;
            if(teams && teams.length > 0){
                if(teams.length > 1){
                    select_team.innerHTML = '<option value="">팀을 선택해주세요.</option>';
                }else{
                    select_team.innerHTML = '';
                }
            }else{
                    select_team.innerHTML = '<option value="">팀이 존재하지 않습니다.</option>';
            }
            teams.forEach(function(team) {
                const option = document.createElement('option');
                option.value = team.team_code;
                option.innerText = team.team_name;
                select_team.appendChild(option);
            });
            if(teams && teams.length == 1){
                select_team.onchange();
            }
        }
    });
}

// 마지막 상담일 가져오기.
function utilsModalLastCounselSelect(){
    const modal_el = document.querySelector('#modal_transfer_team');
    const student_seq = modal_el.querySelector('[data-modal-student-seq]').value;
    const page = "/teacher/counsel/last/select";
    const parameter = {
        student_seq: student_seq
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||"") == "success"){
            const counsel = result.counsel||{};
            if(counsel){
                modal_el.querySelector('[data-counsel-start-date]').innerText = counsel.start_date.substr(0,10).replace(/-/g, '.');
                modal_el.querySelector('[data-counsel-start-time]').innerText = counsel.start_time;
                const counsel_status = counsel.is_counsel=="Y"?"상담완료":"상담대기";
                modal_el.querySelector('[data-is-counsel]').innerText = `(${counsel_status})`;
                if(counsel.is_counsel != 'Y'){
                    modal_el.querySelector('[data-modal-counsel-seq]').value = counsel.id||"";
                }

            }
        }else{}
    });
}

// 학생 상태
function utilsModalStudentType(data){
    const modal_el = document.querySelector('#modal_transfer_team');
    const student_type = modal_el.querySelector('[data-student-type]');

    if((data.goods_detail_seq||'') == '' ){
        student_type.innerText = '신규';
    }
    else{
        student_type.innerText = '재등록';
    }
}

function utilsModalTeacherSelects(){
    const modal_el = document.querySelector('#modal_transfer_team');
    const chage_type = modal_el.querySelector('[data-modal-change-type]').value;

    const teach_type = "select_tag";
    const teach_seq = modal_el.querySelector('[data-modal-teach-seq]').value;
    const region_seq = modal_el.querySelector('[data-modal-regions]').value;
    const team_code = modal_el.querySelector('[data-modal-teams]').value;

    if(!region_seq){
        if(chage_type != 'team') toast('error', '소속을 선택해주세요.')
        return;
    }
    if(!team_code){
        if(chage_type != 'team') toast('error', '팀을 선택해주세요.')
        return;
    }

    utilsModalTeacherSelect(teach_type, teach_seq, region_seq, team_code);
}


// 선생님 선택
function utilsModalTeacherSelect(teach_type , teach_seq, region_seq, team_code){
    const modal_el = document.querySelector('#modal_transfer_team');
    const chage_type = modal_el.querySelector('[data-modal-change-type]').value;

    const page = "/manage/userlist/teacher/select";
    const parameter = {
        teach_seq: teach_seq,
    };
    if(region_seq){ parameter.search_region = region_seq; }
    if(team_code){ parameter.search_team = team_code; }

    queryFetch(page, parameter, function(result){
        if((result.resultCode||"") == "success"){
            const teachers = result.resultData;

            //select tag로 들어올때.
            if(teach_type == 'select_tag'){
                if(teachers && teachers.length > 0){
                    if(teachers.length > 1){
                        modal_el.querySelector('[data-modal-teachers]').innerHTML = '<option value="">배정할 선생님을 선택해주세요.</option>';
                        if(chage_type == 'team')
                            modal_el.querySelector('[data-modal-teachers]').innerHTML = '<option value="">미배정</option>';
                    }else{
                        modal_el.querySelector('[data-modal-teachers]').innerHTML = '';
                    }
                    teachers.forEach(function(teacher){
                        const option = document.createElement('option');
                        option.value = teacher.teach_seq;
                        option.innerText = teacher.teach_name;
                        modal_el.querySelector('[data-modal-teachers]').appendChild(option);
                    });
                }
            }else{
                if(teachers){
                    const teacher = teachers[0];
                    modal_el.querySelector('[data-teach-name]').innerText = teacher.teach_name;
                    modal_el.querySelector('[data-region-name]').innerText = teacher.region_name;
                    modal_el.querySelector('[data-team-name]').innerText = teacher.team_name;
                    modal_el.querySelector('[data-teach-group-name]').innerText = teacher.group_name;
                }
            }
        }
    });
}

//data-modal-chk-is-transfer-agree
function utilsModalTransferSave(callback){
    const modal_el = document.querySelector('#modal_transfer_team');
    const student_seq = modal_el.querySelector('[data-modal-student-seq]').value;
    const counsel_seq = modal_el.querySelector('[data-modal-counsel-seq]').value;
    const change_teach_seq = modal_el.querySelector('[data-modal-teachers]').value;
    const before_teach_seq  = modal_el.querySelector('[data-modal-teach-seq]').value;
    const chage_team_code = modal_el.querySelector('[data-modal-teams]').value;
    const chage_type = modal_el.querySelector('[data-modal-change-type]').value;

    const is_chk = modal_el.querySelector('[data-modal-chk-is-transfer-agree]').checked;
    if(chage_type != 'team' && !change_teach_seq){
        toast( '선생님을 선택해주세요.');
        return;
    }
    if( !chage_team_code){
        toast( '팀을 선택해주세요.');
        return;
    }
    if(!is_chk){
        toast( '배정 변경 절차에 관한 동의 체크해주세요.');
        return;
    }


    let page = "";
    let parameter = "";

    if(chage_type == 'teacher'){
        page = "/manage/useradd/user/insert";
        parameter = {
            grouptype:'student',
            user_key:student_seq,
            change_teach_seq:change_teach_seq,
            log_subject:'담당선생님 변경',
        }

    }else if(chage_type == 'counsel'){
        page = "/teacher/counsel/goods/transfer/confirm/update";
        parameter = {
            counsel_seq: counsel_seq,
            change_teach_seq: change_teach_seq,
            before_teach_seq: before_teach_seq
        }
    }else if(chage_type == 'team'){
        page = "/manage/useradd/user/insert";
        parameter = {
            grouptype:'student',
            change_team_code:chage_team_code,
            user_key:student_seq,
            change_teach_seq:change_teach_seq
        }
    }

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            toast('배정 변경이 완료되었습니다.');
            //모달 닫기.
            modal_el.querySelector('.btn-close').click();

            if(callback) callback();
        }
    });
}

</script>
