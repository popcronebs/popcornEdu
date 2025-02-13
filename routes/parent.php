<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Route::get('/', function () {

// });
//-----------------------------------------------------
// 학부모 페이지 START
// page 연결
Route::get('/parent', 'ParentController@login');
Route::match(['get', 'post'], '/parent/login', 'ParentController@login');
// 학부모 회원가입
Route::get('/parent/register', 'ParentController@register')->name('parent.register');
Route::get('/parent/register/someMethod', 'ParentController@someMethod')->name('parent.register.someMethod');
Route::post('/parent/register/insert', 'ParentController@registerInsert')->name('parent.register.insert');

// 학부모 회원가입 아이디 중복체크
Route::post('/parent/register/username/check', 'ParentController@usernameCheck');

Route::get('/parent/logout', 'ParentController@logout');
Route::post('/parent/change/child', 'ParentController@changeChild');

// 학부모 메인화면
Route::get('/parent/index', 'ParentIndexMtController@list');
// ㄴ 오늘 학습 현황.
Route::post('/parent/index/study/lecture/date/select', 'ParentIndexMtController@studyLectureDateSelect');
// ㄴ 월별 학습현황.
Route::post('/parent/index/study/lecture/month/select', 'ParentIndexMtController@studyLectureMonthSelect');
// ㄴ 학생의 목표학습4 > 성적표
Route::post('/parent/index/study/lecture/detail/select', 'ParentIndexMtController@studyLectureDetailSelect');
//학교 검색
Route::post('/parent/register/school/list', 'ParentController@schoolList');


// 알림설정
Route::get('/parent/noti/settings', 'ParentNotiSetMtController@list');
// ㄴ 알림설정 - 저장 alarmInsert
Route::post('/parent/noti/settings/alarm/insert', 'ParentNotiSetMtController@alarmInsert');

// 알림센터
Route::get('/parent/push/list', 'PushMtController@list');


// 회원수정 /parent/member/info
Route::get('/parent/member/info', 'MemberInfoMtController@list');
// ㄴ 회원 정보 수정 업데이트
Route::post('/parent/member/info/update', 'MemberInfoMtController@userInfoUpdate');
// ㄴ 자녀 추가 등록 childInsert
Route::post('/parent/member/info/child/insert', 'MemberInfoMtController@childInsert');


// 평가 관리.
Route::get('/parent/evaluation', 'EvaluationMtController@list');
// ㄴ 영역별 성적통계 evalSubjectDetailSelect
Route::post('/parent/evaluation/subject/detail/select', 'EvaluationMtController@evalSubjectDetailSelect');
// ㄴ평가관리 첫 중간 정보 evalExamTotalCntSelect
Route::post('/parent/evaluation/exam/total/cnt/select', 'EvaluationMtController@evalExamTotalCntSelect');
// ㄴ 선생님 평가 불러오기.
Route::post('/parent/evaluation/learningg/teacher/evaluation/select', 'EvaluationMtController@evalTeacherEvaluationSelect');
// ㄴ 오답노트 프린트
Route::post('/parent/wrong/note/print', 'WrongNoteMtController@wrongNotePrint');

// 학습관리 = 학생(나의학습)
Route::get('/parent/learning', 'MyStudyMtController@list');
// ㄴ 오늘의 학습 불러오기. [NOTE: 컨트롤러를 옮길지 말지 이후에 결정.]
Route::post('/parent/child/study/today/select', 'ParentLearningMtController@studyPlannerSelect');
// ㄴ 학습 시작시간 불러오기.
Route::post('/parent/child/study/time/select', 'ParentLearningMtController@studyTimeSelect');
// ㄴ 학생 접속중 여부 확인.
Route::post('/parent/child/study/connect/check', 'ParentLearningMtController@studentConnectCheck');


// 학부모 결제내역
Route::get('/parent/payment', 'PaymentMTController@parentList');


// TEST PAGE
Route::get('/parent/test', "ParentController@test");
Route::get('/parent/test1', "ParentController@test1");
Route::get('/parent/test2', "ParentController@test2");
Route::get('/parent/test3', "ParentController@test3");
