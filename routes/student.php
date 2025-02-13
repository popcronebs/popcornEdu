<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Route::get('/', function () {

// });

//-----------------------------------------------------
// 학생 페이지 START
// page 연결
Route::get('/student', 'StudentController@login');
Route::match(['get', 'post'], '/student/login', 'StudentController@login');
Route::get('/student/logout', 'StudentController@logout');
Route::get('/student/temp/main', 'StudentController@tempMain');
Route::get('/student/main', 'StudentController@main');

// ㄴ 오늘의 학습 불러오기.
Route::post('/student/study/today/select', 'StudentController@studyPlannerSelect');
// ㄴ 학습 시작시간 불러오기.
Route::post('/student/study/time/select', 'StudentController@studyTimeSelect');
// ㄴ 학습 시작시 출결하기.(학습시작시간) attend
Route::post('/student/study/start/attend', 'StudentController@attend');
// ㄴ 마지막 접속 시간 업로드.
Route::post('/student/last/connect/time/update', 'StudentController@updateLastConnectTime');

// 오답노트
Route::get('/student/wrong/note', 'WrongNoteMtController@list');
// 오답노트 불러오기.
Route::post('/student/wrong/note/select', 'WrongNoteMtController@wrongSelect');
// 오답노트 - 학습 날짜에 따른 데이터 가져오기.
Route::post('/student/wrong/complete/lectures/select', 'WrongNoteMtController@completeLecturesSelect');
// 오답노트 - 오답노트 다시 풀기.
Route::match(['get', 'post'], '/student/wrong/note/again/exam', 'WrongNoteMtController@againExam');

// 나의학습
Route::get('/student/my/study', 'MyStudyMtController@list');
// ㄴ 요일별 학습시간. weeklyStudyTimeSelect
Route::post('/student/my/study/weekly/time/select', 'MyStudyMtController@weeklyStudyTimeSelect');
// ㄴ 과목별 학습시간 weeklySubjectTimeSelect
Route::post('/student/my/study/weekly/subject/time/select', 'MyStudyMtController@weeklySubjectTimeSelect');
// ㄴ 주간 학습 상세
Route::post('/student/my/study/weekly/learning/detail/select', 'MyStudyMtController@weeklyLearningDetailSelect');
// ㄴ 주간 출견 현황.
Route::post('/student/my/study/weekly/attendance/status/select', 'MyStudyMtController@weeklyAttendanceStatusSelct');
// ㄴ 수강중강좌, 수강완료 강좌 가져오기.
Route::post('/student/my/study/lecture/select', 'MyStudyMtController@lectureSelect');
// ㄴ 찜한 강좌 취소.
Route::post('/student/my/study/lecture/like/cancel', 'MyStudyMtController@lectureLikeCancel');
// ㄴ 학습플래너 추가.(미수강/재수강)
Route::post('/student/my/study/lecture/plan/insert', 'MyStudyMtController@lecturePlanInsert');
// ㄴ 강좌 상세보기 화면 정보 불러오기.
Route::post('/student/my/study/lecture/detail/page/info/select', 'MyStudyMtController@lectrureDetailPageInfoSelect');

// 내 성적표
Route::get('/student/my/score', 'MyScoreMtController@list');
// ㄴ 과목별 성적표 가져오기.
Route::post('/student/my/score/subject/select', 'MyScoreMtController@subjectSelect');
// ㄴ 과목별 특성 가져오기.
Route::post('/student/my/score/subject/good/not/select', 'MyScoreMtController@examMonthGoodOrNotSelect');
// ㄴ 과목별, 주차별 성적 가져오기(그래프용).
Route::post('/student/my/score/subject/jucha/select', 'MyScoreMtController@subjectJuchaSelect');
// ㄴ 과목별 시험 리스트 가져오기.
Route::post('/student/my/score/subject/exam/search/select', 'MyScoreMtController@examSearchSelect');
// ㄴ 과목별 학습플랜 리스트 가져오기.
Route::post('/student/my/score/subject/learn_plan/list', 'MyScoreMtController@learnPlanSearchSelect');


// 학습포인트
Route::get('/student/study/point', 'StudyPointMtController@list');
// ㄴ 학습포인트 히스토리 가져오기.
Route::post('/student/study/point/history/select', 'StudyPointMtController@historySelect');

// 학교공부
Route::get('/student/school/study', 'SchoolStudyMtController@list');
// ㄴ 학교공부 - 학습 리스트 가져오기.
Route::post('/student/school/study/select', 'SchoolStudyMtController@select');
// s 학교공부 학습하기 insert
Route::post('/student/school/study/insert', 'SchoolStudyMtController@insert');

// 학습 / 동영상 시청.
Route::match(['get', 'post'], '/student/study/video', 'StudyVideoMtController@list');
// ㄴ 동영상 좋아요 업데이트. updateLike
Route::post('/student/study/video/like/update', 'StudyVideoMtController@updateLike');
// ㄴ 동영상 마지막 시청시간, 누적 시간 업데이트.
Route::post('/student/study/video/time/update', 'StudyVideoMtController@updateVideoTime');
// ㄴ 오늘의 학습 불러오기. studyPlannerSelect
Route::post('/student/study/video/today/select', 'StudyVideoMtController@studyPlannerSelect');
// ㄴ 학습 완료 업데이트.
Route::post('/student/study/video/complete/update', 'StudyVideoMtController@updateComplete');
// 개념다지기
Route::match(['get', 'post'], '/student/study/concept', 'StudyVideoMtController@concept');
// 문제풀기
Route::match(['get', 'post'],'/student/study/quiz', 'StudyVideoMtController@quiz');
// 문제 불러오기
Route::match(['get', 'post'],'/student/study/quiz/select', 'StudyVideoMtController@getQuizSelect');
// 문제 선택한 문제 불러오기
Route::match(['get', 'post'],'/student/study/quiz/result', 'StudyVideoMtController@getQuizResult');
// 문제풀기 상세 (단일문제)
Route::match(['get', 'post'],'/student/study/quiz/detail/select', 'StudyVideoMtController@getQuizDetail');
// 문제풀기 답 업데이트.
Route::match(['get', 'post'],'/student/study/quiz/insert/or/update', 'StudyVideoMtController@quizInsertOrUpdate');
// 문제풀기 화면접속시 student_exam_results 테이블에 데이터 추가 또는 변경.
Route::match(['get', 'post'],'/student/study/quiz/start', 'StudyVideoMtController@studentExamInsertOrUpdate');
// 정리학습
Route::match(['get', 'post'],'/student/study/summary', 'StudyVideoMtController@summary');
// 단원평가
Route::match(['get', 'post'],'/student/study/unitQuiz', 'StudyVideoMtController@unitQuiz');
// 채점표
Route::get('/student/study/unit/score', 'StudyVideoMtController@score');


// 단원평가
Route::get('/student/unit/test', 'UnitTestMtController@list');
// ㄴ 단원평가 시험 불러오기.
Route::post('/student/unit/test/exam/select', 'UnitTestMtController@examSelect');
// ㄴ 단원평가 시작하기전에 null 인 시험 삭제처리.
Route::post('/student/unit/test/exam/null/delete', 'UnitTestMtController@examNullDelete');
// ㄴ 자유퀴즈 시작하기.
Route::post('/student/study/freeQuiz', 'UnitTestMtController@freeQuiz');


// 이벤트
Route::get('/student/event', 'EventMtController@list');

// 교재
Route::get('/student/book', 'BookMtController@list');

// 전체학습 보기
Route::get('/student/all/study', 'AllStudyMtController@list');
// = 예체능
Route::get('/student/all/study/physical', 'PhysicalMtController@list');
// = 실력키우기
Route::get('/student/all/study/ability/up', 'AbilityUpMtController@list');
// = 창의체험
Route::get('/student/all/study/creative/experience', 'CreativeExperienceMtController@list');
// = 단원별 요점정리
Route::get('/student/all/study/unit/summary', 'UnitSummaryMtController@list');

// 공지사항
Route::get('/student/notice', 'NoticeMtController@list');

// 회원정보
Route::get('/student/member/info', 'MemberInfoMtController@list');
// ㄴ 회원의 이미지(프로필) 업로드
Route::post('/student/member/info/profile/upload', 'MemberInfoMtController@uploadProfile');
// ㄴ 회원의 비밀번호 확인
Route::post('/student/member/info/check/pw', 'MemberInfoMtController@checkPw');
// ㄴ 회원의 정보 변경
Route::post('/student/member/info/update', 'MemberInfoMtController@userInfoUpdate');
// ㄴ 회원 이미지(프로필) 삭제.
Route::post('/student/member/info/delete/profile/img', 'MemberInfoMtController@deleteProfileImg');

// 문제풀기
Route::post('/student/exam/student/exam/result/insert', 'ExamMTController@studentExamResultInsert');
// ㄴ 문제 목록 리스트 가져오기.
Route::post('/student/exam/question/list/select', 'ExamMTController@questionListSelect');

Route::get('/student/member/info/detail', 'MemberInfoMtController@studentInfoDetail');
