@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title', '관리자 계정 관리')

{{-- 네브바 체크 --}}
@section('systemadmin', 'active')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<div class="container-fluid px-4">
    <h1 class="mt-4">선생님 관리</h1>

    <!-- 검색 영역 -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="searchForm" method="GET" class="row g-3">
                <div class="col-md-2">
                    <select class="form-select" name="search_type">
                        <option value="">구분 전체</option>
                        <option value="정교사">정교사</option>
                        <option value="강사">강사</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="search_field">
                        <option value="name">회원명</option>
                        <option value="id">아이디</option>
                        <option value="team">관할팀(or학교)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search_keyword" placeholder="검색어를 입력하세요">
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">등록일</span>
                        <input type="date" class="form-control" name="start_date">
                        <span class="input-group-text">~</span>
                        <input type="date" class="form-control" name="end_date">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">검색</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 테이블 영역 -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                선생님 목록 (<span id="totalCount">0</span>명)
            </div>
            <div>
                <button type="button" class="btn btn-success btn-sm" id="btnExcelDownload">
                    <i class="fas fa-file-excel"></i> 엑셀 다운로드
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr class="text-center">
                        <th width="2%">
                            <input type="checkbox" class="form-check-input" id="checkAll">
                        </th>
                        <th width="8%">구분</th>
                        <th width="15%">회원명/아이디</th>
                        <th width="12%">소속</th>
                        <th width="12%">관할팀(or학교)</th>
                        <th width="10%">등록일</th>
                        <th width="10%">수정내역</th>
                        <th width="8%">이용활성화</th>
                        <th width="10%">권한관리</th>
                    </tr>
                </thead>
                <tbody id="teacherTableBody">
                    <!-- 동적으로 데이터가 들어갈 위치 -->
                </tbody>
            </table>

            <!-- 페이지네이션 -->
            <div class="col-auto">
                <div class="col d-flex justify-content-center align-items-center">
                    <ul class="pagination col-auto gap-3 align-items-center" id="pagination" data-page="1">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script src='https://code.jquery.com/jquery-3.7.1.min.js' integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
    loadTeacherList(1);
    loadTeacherLectureSeries();
    // 전체 체크박스 처리
    $('#checkAll').change(function() {
        $('.check-item').prop('checked', $(this).prop('checked'));
    });

    // 개별 체크박스 변경 시 전체 체크박스 상태 업데이트
    $(document).on('change', '.check-item', function() {
        let allChecked = $('.check-item:not(:checked)').length === 0;
        $('#checkAll').prop('checked', allChecked);
    });
});

function loadTeacherList(list) {

    const page = '/manage/systemauthority/teacher/list/cnt/select';
    const parameter = {
        page: list,
        ...getSearchParams()
    };

    queryFetch(page, parameter, function(result) {
        if (result == null) {
            console.error('데이터 로드 실패');
            $('#teacherTableBody').html('<tr><td colspan="8" class="text-center">데이터 로드 중 오류가 발생했습니다.</td></tr>');
            return;
        }

        if (result && result.data) {
            renderTeacherTable(result.data);
            renderPagination(result);
            $('#totalCount').text(result.total || 0);
        } else {
            console.error('Invalid response format:', result);
            $('#teacherTableBody').html('<tr><td colspan="8" class="text-center">데이터를 불러올 수 없습니다.</td></tr>');
        }
    });
}

function loadTeacherLectureSeries(){
    const page = "/manage/systemauthority/teacher/list/select";
    const parameter = "";

    queryFetch(page, parameter, function(result){
        if (result == null) {
            console.error('데이터 로드 실패');
            $('#lectureTree').html('<div class="text-center">데이터 로드 중 오류가 발생했습니다.</div>');
            return;
        }
        renderLectureTree(result);
    });
}

function getSearchParams() {
    return {
        search_type: $('select[name="search_type"]').val(),
        search_field: $('select[name="search_field"]').val(),
        search_keyword: $('input[name="search_keyword"]').val(),
        start_date: $('input[name="start_date"]').val(),
        end_date: $('input[name="end_date"]').val()
    };
}

function renderTeacherTable(data) {
    if (!Array.isArray(data)) {
        console.error('Invalid data format:', data);
        $('#teacherTableBody').html('<tr><td colspan="8" class="text-center">잘못된 데이터 형식입니다.</td></tr>');
        return;
    }
    let html = '';
    data.forEach(function(teacher) {
        html += `
            <tr class="text-center">
                <td class="align-middle">
                    <input type="checkbox" class="form-check-input check-item" value="${teacher.teach_seq || ''}">
                </td>
                <td class="align-middle">${teacher.group_name || '-'}</td>
                <td class="align-middle">${teacher.teach_name || '-'}<br>(${teacher.teach_id || '-'})</td>
                <td class="align-middle">${teacher.area || '-'}</td>
                <td class="align-middle">${teacher.team_name || '-'}</td>
                <td class="align-middle">${formatDate(teacher.created_at)}</td>
                <td class="align-middle">
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="viewHistory('${teacher.teach_id || ''}')">수정이력</button>
                </td>
                <td class="align-middle">
                    <div class="form-check form-switch d-flex justify-content-center">
                        <input class="form-check-input" type="checkbox" role="switch"
                               ${teacher.is_use === 'Y' ? 'checked' : ''}
                               onchange="updateStatus('${teacher.teach_id || ''}', this.checked)">
                    </div>
                </td>
                <td class="align-middle">
                    <button type="button" class="btn btn-sm btn-primary"
                            onclick="openAuthorityModal('${teacher.teach_id}', '${teacher.teach_name}', '${teacher.teach_seq}')">
                        권한설정
                    </button>
                </td>
            </tr>
        `;
    });
    $('#teacherTableBody').html(html || '<tr><td colspan="8" class="text-center">데이터가 없습니다.</td></tr>');
}

function renderPagination(response) {
    if (!response || !response.links) {
        $('#pagination').html('');
        return;
    }

    let html = '';

    // 이전 버튼
    html += `
        <button href="javascript:void(0)" class="btn p-0 prev calendar_arrow_left"
                ${response.prev_page_url ? '' : 'disabled'}
                onclick="loadTeacherList(${response.current_page > 1 ? response.current_page - 1 : 1})">

        </button>
    `;

    // 페이지 번호
    response.links.forEach(function(link) {
        if (!isNaN(link.label)) {
            html += `
                <span class="page page_num px-3 ${link.active ? 'active' : ''}"
                      onclick="loadTeacherList(${link.label})"
                      ${link.active ? 'disabled' : ''}>
                    ${link.label}
                </span>
            `;
        }
    });

    // 다음 버튼
    html += `
        <button href="javascript:void(0)" class="btn p-0 next gray_arrow_right"
                ${response.next_page_url ? '' : 'disabled'}
                onclick="loadTeacherList(${response.next_page_url ? response.current_page + 1 : response.current_page})">

        </button>
    `;

    $('#pagination').html(html);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    return dateString.split('T')[0];
}

function viewHistory(teachId) {
    if (!teachId) return;
    console.log('수정이력 보기:', teachId);
}

function updateStatus(teachId, status) {
    if (!teachId) return;
    console.log('상태 업데이트:', teachId, status);
}

// 검색 폼 제출 처리
$('#searchForm').on('submit', function(e) {
    e.preventDefault();
    loadTeacherList(1);
});

function openAuthorityModal(teachId, teachName, teachSeq) {
    // 모달 내용 초기화
    $('#teacherInfo').text(`${teachName} (${teachId})`);
    $('#teacherInfo').attr('data-teach-seq', teachSeq);
    $('#currentAuthList').empty();
    $('#selectedLectures').empty();

    // 모달 표시
    const authorityModal = new bootstrap.Modal(document.getElementById('authorityModal'));
    authorityModal.show();
    // document.getElementById('authorityModal').addEventListener('shown.bs.modal', async function () {
    //     console.log(teachSeq);
    //     await loadTeacherLecturePermission(teachSeq);
    // })
    document.querySelectorAll('[data-series-id]').forEach(function(el){el.checked = false});
    document.querySelectorAll('[data-parent-id]').forEach(function(el){el.checked = false});

    loadTeacherLecturePermission(teachSeq)
}

function loadTeacherLecturePermission(teachId){
    const page = "/manage/systemauthority/teacher/permission/select";
    const parameter = {
        teach_seq: teachId
    };
    const authorities = queryFetch(page, parameter, function(result){
        try{
            let data = result.teacher_lecture_permission || [];
            let data_details = result.tlp__details || [];
            // 데이터 변환
            // const authorities = data.map((permission, index) => {
            //     return {
            //         id: permission.code_id,
            //         subject: permission.code_name,
            //         lectures_permissions: permission.lectures_permissions || '{}', // JSON 문자열로 저장된 권한 값
            //         code_pt: permission.code && Array.isArray(permission.code) ? permission.code.map(item =>{
            //             return {[item.id]: item.code_name};
            //         }) : [],
            //         created_at: formatDate(permission.created_at),
            //         created_by: permission.created_by || '관리자'
            //     };
            // });
            renderCurrentAuthorities(data, data_details);

        }catch(e){
            console.log(e);
        }
    });
}

// 날짜 포맷팅 헬퍼 함수
function formatDate(dateString) {
    if (!dateString) return '-';
    return dateString.split('T')[0];
}

function renderCurrentAuthorities(authorities, data_details) {
    let html = '';
    let currentSubject = '';
    authorities.forEach(auth => {
        try{
            // const permissions = JSON.parse(auth.lectures_permissions);
            document.querySelector(`[data-series-id="${auth.code_seq}"]`).checked = true;
            let in_cnt = 0;
            Object.entries(data_details[auth.code_seq]).forEach(([key, values]) => {
                document.querySelector(`[data-lecture-id="${values.code_seq}"]`).checked = true;
                //마지막일때.
                if(data_details[auth.code_seq].length-1 == in_cnt){
                    $(`[data-lecture-id="${values.code_seq}"]`).change();
                }
                in_cnt++;
            });
        }catch(e){console.log(e)}
        html += `
            <tr class="text-center">
                <td><input type="checkbox" class="form-check-input auth-check" value="${auth.id}" data-code-seq="${auth.code_seq}"></td>
                <td>${auth.code_name || '-'}</td>
                <td class="detail-cell">
                    ${data_details[auth.code_seq] ? formatPermissions(data_details[auth.code_seq]) : '0개 강의 허용'}
                </td>
                <td>${formatDate(auth.created_at)}</td>
                <td>${auth.created_by || '-'}</td>
                <td>
                    <button class="btn btn-sm btn-link toggle-details" onclick="toggleDetails(this)">
                        <svg width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.7017 10.2476L12.4686 14.0431C12.8561 14.4335 13.4867 14.4359 13.8772 14.0484L17.7065 10.2476" stroke="#222222" stroke-width="2.5" stroke-miterlimit="10" stroke-linecap="round"/>
                        </svg>
                    </button>
                </td>
            </tr>
            <tr class="detail-row" style="display: none;">
                <td colspan="6">
                    <div class="ms-4">
                        <ul class="list-group list-group-flush">
                            ${auth.code ? formatDetailPermissions(auth, ( data_details[auth.code_seq]||[] )) : ''}
                        </ul>
                    </div>
                </td>
            </tr>`;

    });

    $('#currentAuthList').html(html || '<tr><td colspan="6" class="text-center">부여된 권한이 없습니다.</td></tr>');
}

function formatPermissions(permissionStr) {
    try {
        // const permissions = JSON.parse(permissionStr);
        const allowedCount = permissionStr.length;
        // Object.values(permissions).filter(v => v === true).length;
        return `${allowedCount}개 강의 허용`;
    } catch (e) {
        console.error('Permission parsing error:', e);
        return '-';
    }
}

function formatDetailPermissions(permissions, data_detail) {
    let html = '';

    if (!permissions.code || !Array.isArray(permissions.code)) {
        return '<li class="list-group-item">권한 정보가 없습니다.</li>';
    }

    // const lecturesPermissions = JSON.parse(permissions.lectures_permissions || '{}');
    // lecturesPermissions = {"123":true}
    const lecturesPermissions = {};
    data_detail.forEach(detail => {
        lecturesPermissions[detail.code_seq] = true;
    });


    permissions.code.forEach((item) => {
        if (!item) return;

        const id = item.id;
        const name = item.code_name;

        html += `
            <li class="list-group-item" data-code-pt="${permissions.id}">
                <div class="d-flex justify-content-between align-items-center">
                    <span>${name || '이름 없음'}</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input permission-toggle"
                               type="checkbox"
                               role="switch"
                               id="permission_${id}"
                               ${lecturesPermissions[id] ? 'checked' : ''}
                               data-lecture-id="${id}"
                               onchange="updatePermission('${id}', this.checked)">
                        <label class="form-check-label" for="permission_${id}">
                            ${lecturesPermissions[id] ? '허용' : '거부'}
                        </label>
                    </div>
                </div>
            </li>`;
    });

    return html || '<li class="list-group-item">상세 권한 정보가 없습니다.</li>';
}
function searchLectures() {
    const keyword = $('#searchLecture').val();
    // API 호출하여 강의 검색
    console.log('강의 검색:', keyword);
}

function removeSelectedAuthorities() {
    const code_seqs = $('.auth-check:checked').map((_, el) => el.dataset.codeSeq).get();
    const teach_seq = $('#teacherInfo').attr('data-teach-seq');
    if (!code_seqs.length) {
        return alert('삭제할 권한을 선택해주세요.');
    }

    if (confirm('선택한 권한을 삭제하시겠습니까?')) {
        console.log('삭제할 권한 코드 ID들:', code_seqs);

        const page = "/manage/systemauthority/teacher/permission/delete";
        const parameter = { code_seqs: code_seqs, teach_seq: teach_seq};

        queryFetch(page, parameter, (result) => {
            if (result?.status === "success") {
                alert(result.message);
                var authorityModal = bootstrap.Modal.getInstance(document.getElementById('authorityModal'));
                authorityModal.hide();
            } else {
                console.error('Error:', result);
            }
        });
    }
}

// 트리 토글 기능
$(document).on('click', '.fa-caret-right', function() {
    $(this).toggleClass('fa-rotate-90');
    $(this).closest('li').children('ul').slideToggle();
});

// 체크박스 계층 처리
$(document).on('change', '#lectureTree input[type="checkbox"]', function() {
    const checked = $(this).prop('checked');
    // 하위 항목 체크/언체크
    $(this).closest('li').find('input[type="checkbox"]').prop('checked', checked);
    updateSelectedLectures();
});

function updateSelectedLectures() {
    const selected = $('#lectureTree input[type="checkbox"]:checked').map(function() {
        return $(this).siblings('span').text();
    }).get();

    let html = '';
    selected.forEach(item => {
        html += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                ${item}
                <div class="form-check form-switch d-flex justify-content-center">
                    <input class="form-check-input" type="checkbox" role="switch" checked>
                </div>
            </li>
        `;
    });
    $('#selectedLectures').html(html);
}

function removeSelected(btn) {
    const text = $(btn).parent().text().trim();
    $(`#lectureTree span:contains('${text}')`).siblings('input[type="checkbox"]').prop('checked', false);
    updateSelectedLectures();
}

function saveAuthority() {
    const selected = $('#lectureTree input[type="checkbox"]:checked').map(function() {
        return {
            text: $(this).siblings('span').text(),
            level: $(this).closest('ul').parents('li').length
        };
    }).get();

    console.log('저장할 권한:', selected);
    var authorityModal = bootstrap.Modal.getInstance(document.getElementById('authorityModal'));
    authorityModal.hide();
}

// 토글 기능 수정
function toggleDetails(btn) {
    const row = $(btn).closest('tr');
    const detailRow = row.next('.detail-row');
    const svg = $(btn).find('svg');

    detailRow.toggle();

    // SVG 회전 애니메이션
    if (detailRow.is(':visible')) {
        svg.css('transform', 'rotate(180deg)');
    } else {
        svg.css('transform', 'rotate(0deg)');
    }
}

// 권한 업데이트 함수 추가
function updatePermission(lectureId, isAllowed) {
    const teacherSeq = $('#teacherInfo').attr('data-teach-seq');
    const codePt = $(`#permission_${lectureId}`).closest('li').data('code-pt');

    // 권한 변경 데이터 구성
    const data = {
        teach_seq: teacherSeq,
        code_seq: [codePt], // 상위 과목 코드
        lectures_permission: JSON.stringify({
            [lectureId]: isAllowed
        }),
        code_seqs:[lectureId],
        is_one:'Y',
        isAllowed:isAllowed
    };

    // 토글 버튼 비활성화
    $(`#permission_${lectureId}`).prop('disabled', true);

    const page = '/manage/systemauthority/teacher/permission/insert/update';
    queryFetch(page, data, function(result) {
        if (result && result.status === 'success') {
            // 레이블 업데이트
            $(`label[for="permission_${lectureId}"]`).text(isAllowed ? '허용' : '거부');
            console.log(`강의 ${lectureId} 권한이 ${isAllowed ? '허용' : '거부'}로 변경되었습니다.`);
            $(`#lecture_${lectureId}`).prop('checked', isAllowed);
            $(`#lecture_${lectureId}`).change();

        } else {
            // 실패 시 토글 원상복구
            $(`#permission_${lectureId}`).prop('checked', !isAllowed);
            $(`label[for="permission_${lectureId}"]`).text(!isAllowed ? '허용' : '거부');
            alert('권한 변경에 실패했습니다.');
        }
        // 토글 버튼 활성화
        $(`#permission_${lectureId}`).prop('disabled', false);
    });
}

// 전체 체크박스 처리 수정
$(document).on('change', '#currentAuthCheckAll', function() {
    const isChecked = $(this).prop('checked');
    $('.auth-check').prop('checked', isChecked);
});

// 개별 체크박스 처리 수정
$(document).on('change', '.auth-check', function() {
    const allChecked = $('.auth-check:not(:checked)').length === 0;
    $('#currentAuthCheckAll').prop('checked', allChecked);
});

// 강의 트리 렌더링 함수 추가
function renderLectureTree(data) {
    let html = '<ul class="list-unstyled">';

    // 현재 선생님의 권한 데이터 가져오기
    const teacherSeq = $('#teacherInfo').attr('data-teach-seq');
    const page = "/manage/systemauthority/teacher/permission/select";
    const parameter = {
        teach_seq: teacherSeq
    };

    // 권한 데이터를 가져온 후 트리 렌더링
    queryFetch(page, parameter, function(result) {
        const permissions = result.teacher_lecture_permission || [];
        const existingPermissions = {};

        // 기존 권한 데이터 정리
        permissions.forEach(permission => {
            const lecturesPermissions = JSON.parse(permission.lectures_permissions || '{}');
            existingPermissions[permission.code_id] = lecturesPermissions;
        });
        data.forEach(series => {
            // 하위 항목들의 권한 상태 확인
            let hasCheckedChild = false;
            if (series.sub_objects && series.sub_objects.length > 0) {
                hasCheckedChild = series.sub_objects.some(sub =>
                    existingPermissions[series.id] && existingPermissions[series.id][sub.id] === true
                );
            }

            html += `
                <li>
                    <div class="d-flex align-items-center mb-2">
                        <button class="btn btn-sm btn-link toggle-details p-0 me-2" onclick="toggleTreeDetails(this)">
                            <svg width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.7017 10.2476L12.4686 14.0431C12.8561 14.4335 13.4867 14.4359 13.8772 14.0484L17.7065 10.2476"
                                      stroke="#222222" stroke-width="2.5" stroke-miterlimit="10" stroke-linecap="round"/>
                            </svg>
                        </button>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input parent-check"
                                   id="series_${series.id}"
                                   data-series-id="${series.id}"
                                   ${hasCheckedChild ? 'checked' : ''}>
                            <label class="form-check-label" for="series_${series.id}">
                                ${series.code_name}
                            </label>
                        </div>
                    </div>`;

            if (series.sub_objects && series.sub_objects.length > 0) {
                html += '<ul class="list-unstyled ms-4" style="display: none;">';
                series.sub_objects.forEach(sub => {
                    // 기존 권한 확인
                    const hasPermission = existingPermissions[series.id] &&
                                        existingPermissions[series.id][sub.id] === true;

                    html += `
                        <li>
                            <div class="d-flex align-items-center mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input child-check"
                                           id="lecture_${sub.id}"
                                           data-parent-id="${series.id}"
                                           data-lecture-id="${sub.id}"
                                           data-code-pt="${series.id}"
                                           ${hasPermission ? 'checked' : ''}>
                                    <label class="form-check-label" for="lecture_${sub.id}">
                                        ${sub.code_name}
                                    </label>
                                </div>
                            </div>
                        </li>`;
                });
                html += '</ul>';
            }

            html += '</li>';
        });

        html += '</ul>';
        $('#lectureTree').html(html);

        // 체크박스 이벤트 핸들러 초기화
        initCheckboxHandlers();
    });
}

// 부모 체크박스 상태 업데이트 함수 수정
function updateParentCheckboxes() {
    $('.parent-check').each(function() {
        const seriesId = $(this).data('series-id');
        const childChecks = $(`input[data-parent-id="${seriesId}"]`);
        // 하나라도 체크되어 있으면 부모도 체크
        const hasChecked = childChecks.length > 0 && childChecks.filter(':checked').length > 0;
        $(this).prop('checked', hasChecked);
    });
}

// initCheckboxHandlers 함수도 수정
function initCheckboxHandlers() {
    // 상위 체크박스 변경 이벤트
    $('.parent-check').change(function() {
        const seriesId = $(this).data('series-id');
        const isChecked = $(this).prop('checked');

        // 하위 체크박스들 상태 변경
        $(`input[data-parent-id="${seriesId}"]`).prop('checked', isChecked);
        updateSelectedLectures();
    });

    // 하위 체크박스 변경 이벤트
    $('.child-check').change(function() {
        const parentId = $(this).data('parent-id');
        const parentCheckbox = $(`#series_${parentId}`);
        const siblings = $(`input[data-parent-id="${parentId}"]`);
        // 하나라도 체크되어 있으면 부모도 체크
        const hasChecked = siblings.filter(':checked').length > 0;

        parentCheckbox.prop('checked', hasChecked);
        updateSelectedLectures();
    });
}

// 트리 토글 함수 수정
function toggleTreeDetails(btn) {
    const row = $(btn).closest('li');
    const subList = row.find('ul').first();
    const svg = $(btn).find('svg');

    subList.slideToggle();

    if (subList.is(':visible')) {
        svg.css('transform', 'rotate(180deg)');
    } else {
        svg.css('transform', 'rotate(0deg)');
    }
}

// 선택된 강의 데이트 함수 수정
function updateSelectedLectures() {
    const selectedLectures = [];

    // 선택된 하위 항목들 수집
    $('.child-check:checked').each(function() {
        const lectureId = $(this).data('lecture-id');
        const lectureName = $(this).next('label').text().trim();
        const parentId = $(this).data('parent-id');
        const parentName = $(`#series_${parentId}`).next('label').text().trim();

        selectedLectures.push({
            id: lectureId,
            name: lectureName,
            parent: parentName
        });
    });

    // 선택된 강의 목록 렌더링
    let html = '';
    selectedLectures.forEach(lecture => {
        html += `
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">${lecture.parent}</small>
                        <br>${lecture.name}
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" checked
                               onchange="removeFromSelection(${lecture.id}, this)">
                    </div>
                </div>
            </li>`;
    });

    $('#selectedLectures').html(html || '<li class="list-group-item text-center">선택된 강의가 없습니다.</li>');
}

// 선택 해제 함수 추가
function removeFromSelection(lectureId, switchElement) {
    if (!$(switchElement).prop('checked')) {
        $(`#lecture_${lectureId}`).prop('checked', false).trigger('change');
    }
}


function saveSelectedPermissions() {
    const selectedLectures = $('#selectedLectures input[type="checkbox"]').map(function() {
        return {
            code_id: $(this).data('lecture-id'),
            permission: $(this).prop('checked'),
            code_pt: $(this).data('code-pt')
        }
    }).get();
    const code_seqs = $('#selectedLectures input[type="checkbox"]:checked').map(function() {
        return $(this).data('lecture-id')
    }).get();


    if (selectedLectures.length === 0) {
        alert('선택된 강의가 없습니다.');
        return;
    }

    const teacherId = $('#teacherInfo').text().match(/\((.*?)\)/)[1];
    const teacherSeq = $('#teacherInfo').attr('data-teach-seq');

    // Collect all checked series IDs into an array
    const code_seq = $('input[data-series-id]:checked').map(function() {
        return $(this).data('series-id');
    }).get();

    // 권한 데이터 구성
    const permissionsArray = code_seq.map(codeId => {
        const permissions = {};
        selectedLectures.forEach(lecture => {
            if (lecture.code_pt === codeId) {
                permissions[lecture.code_id] = lecture.permission;
            }
        });
        return permissions;
    });

    const data = {
        teach_seq: teacherSeq,
        code_seq: code_seq, // Now an array
        lectures_permission: JSON.stringify(permissionsArray),
        code_seqs:code_seqs
    };

    // 저장 버튼 비활성화 및 로딩 표시
    const saveBtn = $('.card-footer .btn-primary');
    saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>저장 중...');
    const page = '/manage/systemauthority/teacher/permission/insert/update';
    const parameter = data;
    queryFetch(page, parameter, function(result) {
        if (result == null) {
            console.error('권한 저장 실패');
            alert('권한 저장 중 오류가 발생했습니다.');
            saveBtn.prop('disabled', false).text('선택 강의 권한 적용');
            return;
        }

        if (result && result.status === 'success') {
            alert(result.message);

            $('#selectedLectures').empty();
            $('.child-check, .parent-check').prop('checked', false);
            var authorityModal = bootstrap.Modal.getInstance(document.getElementById('authorityModal'));
            authorityModal.hide();
        } else {
            console.error('Invalid response format:', result);
            alert('권한 저장에 실패했습니다.');
        }
        saveBtn.prop('disabled', false).text('선택 강의 권한 적용');
    });
}

// updateSelectedLectures 함수 수정 (토글 스위치 상태 저장 추가)
function updateSelectedLectures() {
    const selectedLectures = [];

    $('.child-check:checked').each(function() {
        const lectureId = $(this).data('lecture-id');
        const lectureName = $(this).next('label').text().trim();
        const parentId = $(this).data('parent-id');
        const parentName = $(`#series_${parentId}`).next('label').text().trim();
        const code_pt = $(this).data('code-pt');
        selectedLectures.push({
            id: lectureId,
            name: lectureName,
            parent: parentName,
            permission: true,  // 기본값 true로 설정
            code_pt: code_pt,
        });
    });

    let html = '';
    selectedLectures.forEach(lecture => {
        html += `
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">${lecture.parent}</small>
                        <br>${lecture.name}
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" checked
                               data-lecture-id="${lecture.id}"
                               data-code-pt="${lecture.code_pt}"
                               onchange="updatePermissionState(${lecture.id}, ${lecture.code_pt}, this.checked)">
                    </div>
                </div>
            </li>`;
    });

    $('#selectedLectures').html(html || '<li class="list-group-item text-center">선택된 강의가 없습니다.</li>');
}

{{-- function saveAuthority (){

} --}}
</script>

<style>
.toggle-details svg {
    transition: transform 0.3s ease;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-switch .form-check-input {
    width: 2em;
}

.form-check-label {
    margin-left: 0.5em;
    min-width: 2.5em;
}

/* 모달 포커스 관련 스타일 */
.modal {
    outline: none;
}

.modal-content {
    outline: none;
}

.modal-header .btn-close:focus {
    box-shadow: none;
    outline: none;
}
</style>


<div class="modal fade" id="authorityModal" tabindex="-1" role="dialog" aria-labelledby="authorityModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authorityModalLabel">강의 권한 설정</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>선생님 정보:</strong> <span id="teacherInfo"></span></p>
                    </div>
                </div>

                <!-- 탭 네비게이션 -->
                <ul class="nav nav-tabs" id="authorityTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="current-tab" data-bs-toggle="tab"
                                data-bs-target="#current" type="button" role="tab">
                            현재 권한 목록
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="add-tab" data-bs-toggle="tab"
                                data-bs-target="#add" type="button" role="tab">
                            권한 추가
                        </button>
                    </li>
                </ul>

                <!-- 탭 컨텐츠 -->
                <div class="tab-content" id="authorityTabContent">
                    <!-- 현재 권한 목록 탭 -->
                    <div class="tab-pane fade show active" id="current" role="tabpanel">
                        <div class="p-3">
                            <div class="d-flex justify-content-between mb-3">
                                <h6>부여된 권한 목록</h6>
                                <button class="btn btn-danger btn-sm" onclick="removeSelectedAuthorities()">
                                    선택 권한 삭제
                                </button>
                            </div>
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">
                                            <input type="checkbox" class="form-check-input" id="currentAuthCheckAll">
                                        </th>
                                        <th width="30%">과목</th>
                                        <th width="30%">세부 과목</th>
                                        <th width="15%">부여일</th>
                                        <th width="15%">부여자</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="currentAuthList">
                                    <!-- 동적으로 채워질 현재 권한 목록 -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 권한 추가 탭 -->
                    <div class="tab-pane fade" id="add" role="tabpanel">
                        <div class="p-3">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="과목/강의 검색"
                                               id="searchLecture">
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="searchLectures()">검색</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- 계층형 권한 트리 -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">강의 목록</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="lectureTree">
                                                <!-- 트리 구조 예시 -->
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-caret-right me-2"></i>
                                                            <input type="checkbox" class="form-check-input me-2">
                                                            <span>초등 수학</span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-caret-right me-2"></i>
                                                            <input type="checkbox" class="form-check-input me-2">
                                                            <span>초등 과학</span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- 선택된 권한 목록 -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">선택된 강의</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group" id="selectedLectures">
                                                <!-- 동적으로 채워질 선택된 강의 목록 -->
                                            </ul>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" class="btn btn-primary w-100" onclick="saveSelectedPermissions()">
                                                선택 강의 권한 적용
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <!-- <button type="button" class="btn btn-primary" onclick="saveAuthority()">저장</button> -->
            </div>
        </div>
    </div>
</div>

@endsection
