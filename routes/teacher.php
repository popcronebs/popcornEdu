<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Route::get('/', function () {

// });

//-----------------------------------------------------
// 선생님 페이지 START
// page 연결
Route::get('/teacher', 'TeacherController@login');
Route::match(['get', 'post'], '/teacher/login', 'TeacherController@login');
Route::get('/teacher/logout', 'TeacherController@logout');
// 비방과후 기본 메인 / 소속선택
Route::get('/teacher/main', 'TeacherController@main');

//방과후 기본 메인 / 소속선택
Route::get('/teacher/main/after', 'TeacherMainMtController@mainAfter');
// ㄴ 방과후 수업(클래스) insert
Route::post('/teacher/main/after/class/insert', 'TeacherMainMtController@classInsert');
// ㄴ 방과후 수업(클래스)
Route::post('/teacher/main/after/class/select', 'TeacherMainMtController@classSelect');
// ㄴ 방과후 학생 불러오기
Route::post('/teacher/main/after/student/select', 'TeacherMainMtController@studentSelect');
// ㄴ 방과후 수업시작하기
Route::post('/teacher/main/after/class/start', 'TeacherMainMtController@classStart');
// ㄴ 방과후 수업시작하기 > 학습신호등의 학생 리스트 가져오기.
Route::post('/teacher/main/after/class/student/select', 'TeacherMainMtController@classStudentSelect');
// ㄴ 방과후 수업시작하기 > 출석하기.
Route::post('/teacher/main/after/class/student/attend', 'TeacherMainMtController@classStudentAttend');
// ㄴ 방과후 수업시작하기 > 출석 취소하기.
Route::post('/teacher/main/after/class/student/attend/cancel', 'TeacherMainMtController@classStudentAttendCancel');
// ㄴ 방과후 수업시작하기 > 결석사유 넣기.
Route::post('/teacher/main/after/class/student/attend/absent/reason', 'TeacherMainMtController@classStudentAttendAbsentReason');
// ㄴ 방과후 수업시작하기 > 보강일 변경/지정.
Route::post('/teacher/main/after/class/student/reinforcement/date/insert', 'TeacherMainMtController@classStudentReinforcementDateInsert');
// ㄴ 방과후 수업시작하기 > 금일 보강일정 불러오기.
Route::post('/teacher/main/after/class/reinforcement/date/select', 'TeacherMainMtController@classReinforcementDateSelect');
// ㄴ 방과후 수업시작하기 > 금일 보강완료
Route::post('/teacher/main/after/class/reinforcement/date/complete', 'TeacherMainMtController@classReinforcementDateComplete');
// ㄴ 방과후 수업시작하기 > 상단 오늘의 현황 카운트 불러오기.
Route::post('/teacher/main/after/class/today/status/count', 'TeacherMainMtController@classTodayStatusCount');
// ㄴ 방과후 선생님 메인 > 오늘의 수업 요약 classTodayStatusCountMain
Route::post('/teacher/main/after/class/today/status/count/main', 'TeacherMainMtController@classTodayStatusCountMain');

// 방과후 학습관리 AfterLearningMTController@list
Route::get('/teacher/after/learning/management', 'AfterLearningMTController@list');
// ㄴ 방과후 학습관리 > 일괄 수업종료.
Route::post('/teacher/after/learning/management/class/student/attend/end', 'AfterLearningMTController@classStudentAttendEnd');
// ㄴ 방과후 학습관리 > 출결현황 > 보강완료
Route::post('/teacher/after/learning/management/class/student/reinforcement/end', 'AfterLearningMTController@classStudentReinforcementEnd');

// 방과후 학습관리 > 상세 페이지
Route::match(['get', 'post'], '/teacher/after/learning/management/detail', 'AfterLearningMTController@detail');
// 방과후 학습관리 > 상세 페이지 > 학습일지 INSERT
Route::post('/teacher/after/learning/management/detail/insert', 'AfterLearningMTController@detailInsert');
// 방과후 학습관리 > 상세페이지 > 학습일지 조회.
Route::post('/teacher/after/learning/management/detail/select', 'AfterLearningMTController@detailSelect');
// 방과후 학습관리 > 상세페이지 > 출결리스트 조회.
Route::post('/teacher/after/learning/management/detail/attend/select', 'AfterLearningMTController@detailAttendSelect');


// 첫로그인 방과후 /teacher/after/first_login
Route::get('/teacher/after/first/login', 'TeacherController@afterFirstLogin');
// 첫로그인 비방과후 /teacher/first/login
Route::get('/teacher/first/login', 'TeacherController@firstLogin');
// 첫로그인 > 방과후 선생님 정보 저장.
Route::post('/teacher/after/first/login/insert', 'TeacherController@afterFirstLoginInsert');

// 알림센터(쪽지함)
Route::get('/teacher/messenger', 'MessengerController@list');
// ㄴ 학생 선택
Route::post('/teacher/messenger/student/select', 'MessengerController@studentSelect');
// ㄴ 쪽지 보내기
Route::post('/teacher/messenger/send/insert', 'MessengerController@sendInsert');
// ㄴ 선생님 쪽지함 인트로 문구 변경.
Route::post('/teacher/messenger/intro/insert', 'MessengerController@introInsert');
// ㄴ 쪽지함에서 쪽지 리스트 가져오기.
Route::post('/teacher/messenger/select', 'MessengerController@select');
// ㄴ 쪽지함 쪽지 삭제
Route::post('/teacher/messenger/delete', 'MessengerController@delete');
// ㄴ 쪽지함 답변 보내기
Route::post('/teacher/messenger/comment/insert', 'MessengerController@commentInsert');
// ㄴ 담당선생님 프로필 사진 업로드.
Route::post('/teacher/messenger/info/profile/upload', 'MemberInfoMtController@uploadProfile');


// 학생정보관리
Route::get('/teacher/student', 'StudentMtController@list');
// ㄴ 학생 목록 조회
Route::post('/teacher/student/select', 'StudentMtController@studentSelect');
// ㄴ 학생 정보관리리 > 학생 포인트 리스트.
Route::post('/teacher/student/goods/select', 'StudentMtController@goodsSelect');
// ㄴ 학생 정보 상세 보기 /teacher/student/detail
Route::post('/teacher/student/detail', 'StudentMtController@studentDetail');

// 학생정보관리 방과후
Route::get('/teacher/student/after', 'StudentMtController@afterList');
// ㄴ 학생 정보관리 > 상세보기 방과후.
Route::post('/teacher/student/after/detail', 'StudentMtController@afterDetail');
// ㄴ 학생 정보관리 > 상세보기 저장 기능.
Route::post('/teacher/student/after/detail/update', 'StudentMtController@afterDetailUpdate');

// 학습 상담 관리
Route::get('/teacher/counsel', 'TeachCounselMtController@list');
// ㄴ 담당 학생 목록
Route::post('/teacher/counsel/student/select', 'TeachCounselMtController@studentSelect');
// ㄴ 상담 등록
Route::post('/teacher/counsel/insert', 'TeachCounselMtController@insert');
// ㄴ 상담 SELECT.
Route::post('/teacher/counsel/select', 'TeachCounselMtController@select');
// ㄴ 상담 캘린더 목록 SELECT
Route::post('/teacher/counsel/calendar/select', 'TeachCounselMtController@calendarSelect');
// ㄴ 상담 목록에서 상담일 변경기능.
Route::post('/teacher/counsel/change/date/update', 'TeachCounselMtController@changeDateUpdate');
// ㄴ 상담 오늘이전 마지막 상담정보 불러오기. lastCounselSelect
Route::post('/teacher/counsel/last/select', 'TeachCounselMtController@lastCounselSelect');

// 이용권 상담 관리
Route::get('/teacher/counsel/goods', 'TeachCounselMtController@goodsList');
// ㄴ 이용권 상담 카운트 가져오기.
Route::post('/teacher/counsel/goods/count/select', 'TeachCounselMtController@goodsCountSelect');
// ㄴ 이용권 상담 이관요청.
Route::post('/teacher/counsel/goods/transfer/insert', 'TeachCounselMtController@transferInsert');
// ㄴ 이용권 상담 선택 상태변경
Route::post('/teacher/counsel/goods/is/counsel/update', 'TeachCounselMtController@isCounselUpdate');
// ㄴ 이용권 이관 요청 승인
Route::post('/teacher/counsel/goods/transfer/confirm/update', 'TeachCounselMtController@transferConfirmUpdate');


// 결제(이용권) 관리
Route::get('/teacher/paylist', 'TeachPayListMtController@list');
// ㄴ 결제(이용권) 상세
Route::get('/teacher/paylist/detail', 'TeachPayListMtController@detail');


// 회원정보관리(개인정보)

// 선생님 마이페이지
Route::get('/teacher/member/info', 'MemberInfoMtController@list');
// 선생님 정보 상세(마이페이지와 겹침.) teacher_info_detail
Route::match(['get', 'post'], '/teacher/detail', 'MemberInfoMtController@teacherInfoDetail');
// ㄴ 선생님 정보 상세 > 선생님 정보 수정.
Route::post('/teacher/member/info/update', 'MemberInfoMtController@userInfoUpdate');

// 알림센터
Route::get('/teacher/push/list', 'PushMtController@list');
// ㄴ 알림센터 알림 가져오기.
Route::post('/teacher/push/select', 'PushMtController@select');
// ㄴ 알림센터 알림 전체 읽음 처리.
Route::post('/teacher/push/all/read', 'PushMtController@allRead');
// ㄴ 알림센터 알림 전체 삭제 처리.
Route::post('/teacher/push/all/delete', 'PushMtController@allDelete');


// 선생님 정보 관리
Route::get('/teacher/manage', 'UserMTController@teacherList');


// 선생님 그룹별 카운트 가져오기.
Route::post('/teacher/manage/group/count/select', 'UserMTController@groupCountSelect');

// 유저 일괄등록.
Route::post('/teacher/users/add/excel', 'UserMTController@addExcel');
// ㄴ 유저 이괄등록 저장
Route::post('/teacher/users/add/excel/insert', 'UserMTController@userArrInsert');
//유저 개별 등록 페이지
Route::match(['get', 'post'], '/teacher/users/add/list', 'UserMTController@userAddList');

// 방과후 학생 개별 등록 페이지
Route::match(['get', 'post'], '/teacher/after/users/add/list', 'UserMTController@afterUserAddList');
// ㄴ 방과후 학생 개별 등록 -> 삭제 기능.
Route::post('/teacher/after/users/delete', 'UserMTController@userDelete');


// 대쉬 보드
Route::get('/teacher/dashboard', 'DashBoardMtController@list');
// ㄴ 대쉬보드 > todolist 불러오기.
Route::post('/teacher/dashboard/todolist/select', 'DashBoardMtController@todolistSelect');
// ㄴ 대쉬보드 > todolist update
Route::post('/teacher/dashboard/todolist/update', 'DashBoardMtController@todolistUpdate');
// ㄴ 대쉬보드 > 총괄 > 팀별 결제 완료 리스트 불러오기.
Route::post('/teacher/dashboard/team/cnt/select', 'DashBoardMtController@teamCntSelect');
// ㄴ 대쉬보드 > todolist insert
Route::post('/teacher/dashboard/todolist/insert', 'DashBoardMtController@todolistInsert');


// 수업관리
Route::get('/teacher/after/class/management', 'ClassManagementMtController@list');
// ㄴ 수업관리 > 수업리스트 가져오기.
Route::post('/teacher/after/class/management/select', 'ClassManagementMtController@classSelect');
// ㄴ 수업관리 > 오른쪽 상세리스트 가져오기.
Route::post('/teacher/after/class/management/detail/select', 'ClassManagementMtController@classDetailSelect');
// ㄴ 수업관리 > 보충 수업 삭제
Route::post('/teacher/after/class/management/absent/ref/delete', 'ClassManagementMtController@absentRefDelete');
// ㄴ 수업관리 > 보강 학생 목록 가져오기.
Route::post('/teacher/after/class/management/absent/ref/select', 'ClassManagementMtController@absentRefSelect');
// ㄴ 선택 학생 보강 추가.
Route::post('/teacher/after/class/management/absent/ref/insert', 'ClassManagementMtController@absentRefInsert');
// ㄴ 보강 리스트 전체목록보기 리스트 불러오기.
Route::post('/teacher/after/class/management/absent/ref/all/select', 'ClassManagementMtController@absentRefAllSelect');
// ㄴ 수업관리 > 전체목록보기 > 일정변경.
Route::post('/teacher/after/class/management/absent/ref/date/update', 'ClassManagementMtController@absentRefDateUpdate');

