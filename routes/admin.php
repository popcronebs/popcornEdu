<?php
$is_debug = true;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Route::get('/', function () {
//     if($_SERVER['HTTP_HOST'] == "sdang.acaunion.com"){
//         return redirect('/manage');
//     }
// });

//-----------------------------------------------------
// 관리자 페이지 START
//admin page 연결
Route::get('/manage', 'AdminController@login');
Route::match(['get', 'post'], '/manage/login', 'AdminController@login');
Route::get('/manage/logout', 'AdminController@logout');
Route::get('/manage/main', 'AdminController@main');

//사용자목록
Route::get('/manage/userlist', 'UserMTController@list');
//사용자등록
Route::get('/manage/useradd', 'UserMTController@add');
// ㄴ사용자목록
Route::post('/manage/userlist/group/cnt/select', 'UserMTController@groupListCntSelect');
// ㄴ사용자등록
Route::post('/manage/useradd/user/insert', 'UserMTController@userInsert');
// ㄴ 사용자등록 - 아이디 확인
Route::post('/manage/useradd/user/id/check', 'UserMTController@userIdCheck');
// ㄴ 사용자등록 - 학부모, 학생 검색
Route::post('/manage/useradd/user/select', 'UserMTController@userSelect');
// ㄴ 사용자그룹저장
Route::post('/manage/userlist/group/insert', 'UserMTController@groupInsert');
// ㄴ 사용자 등록 시 모든 소속 SELECT
Route::post('/manage/useradd/region/select', 'UserMTController@regionSelect');
// ㄴ 사용자 등록 시 팀 SELECT
Route::post('/manage/useradd/team/select', 'UserMTController@teamSelect');
// ㄴ 사용자 / 학생 이용권 상세 내역 SELECT
Route::post('/manage/userlist/goods/detail/select', 'UserMTController@goodsDetailSelect');
// ㄴ 사용자 등록 시 모든 그룹 SELECT
Route::post('/manage/useradd/group/select', 'UserMTController@groupSelect');
// ㄴ 사용자 등록 샘플 엑셀 다운
Route::get('/manage/useradd/filedown', 'UserMTController@fileDown');
// ㄴ 사용자목록 - 모든 그룹 사용자 검색
Route::post('/manage/userlist/user/select', 'UserMTController@getUserList');
// ㄴ 사용자목록 - 학생 검색
Route::post('/manage/userlist/student/select', 'UserMTController@studentSelect');
// ㄴ 사용자목록 - 학부모 검색
Route::post('/manage/userlist/parent/select', 'UserMTController@parentSelect');
// ㄴ 사용자목록 - 선생님 검색
Route::post('/manage/userlist/teacher/select', 'UserMTController@teacherSelect');
// ㄴ 사용자목록 - 사용자 사용여부 업데이트
Route::post('/manage/userlist/user/use/update', 'UserMTController@userUseUpdate');
// ㄴ 사용자목록 - 사용자 수정 내역 기록
Route::post('/manage/userlist/user/info/update/histoy', 'UserMTController@userInfoUpdateHistoy');
// ㄴ 사용자 포인트 추가.
Route::post('/manage/userlist/point/insert', 'UserMTController@pointInsert');
// ㄴ 사용자 포인트 내역 조회
Route::post('/manage/userlist/point/history/select', 'UserMTController@pointHistorySelect');
// ㄴ 사용자 담당 선생님 변경.
Route::post('/manage/userlist/teacher/charge/update', 'UserMTController@teacherChargeUpdate');
// ㄴ 사용자 담당 학생 수치 가져오기.
Route::post('/manage/userlist/teacher/charge/stcnt/select', 'UserMTController@teacherChargeStcntSelect');
// ㄴ 사용자(학생)의 이용권 끝일 늘리기.
Route::post('/manage/userlist/day/update', 'UserMTController@dayUpdate');
// ㄴ 사용자(선생님) 소속/관할 변경
Route::post('/manage/userlist/teacher/team/update', 'UserMTController@teacherTeamUpdate');
// ㄴ 사용자(선생님) 재직상태 변경
Route::post('/manage/userlist/teacher/status/update', 'UserMTController@teacherStatusUpdate');
// ㄴ 사용자 그룹 없는 회원 그룹 수정.
Route::post('/manage/userlist/group/update', 'UserMTController@userGroupUpdate');
// ㄴ 사용자 리스트에서 수정 후 저장
Route::post('/manage/userlist/user/update', 'UserMTController@userListUpdate');
// ㄴ 사용자 이용권 및 이용권 로그 조회
Route::post('/manage/userlist/goods/day/select', 'UserMTController@goodsDaySelect');
// ㄴ 사용자 이용권 정지 저장
Route::post('/manage/userlist/goods/day/stop/insert', 'UserMTController@goodsDayStopInsert');
// ㄴ 사용자 이용권 상세내역 변경 로그 조회
Route::post('/manage/userlist/goods/detail/log/select', 'UserMTController@goodsDetailLogSelect');

// 아이디찾기
Route::get('/user/id/find', 'UserMTController@idFind');
// 비밀번호 찾기
Route::get('/user/pw/find', 'UserMTController@pwFind');


// 상품(이용권) 관리 goods
Route::get('/manage/goods', 'GoodsMTController@goods');
// ㄴ 상품(이용권) 등록
Route::post('/manage/goods/insert', 'GoodsMTController@goodsInsert');
// ㄴ 상품(이용권) 리스트
Route::post('/manage/goods/select', 'GoodsMTController@goodsSelect');
// ㄴ 상품(이용권) 삭제
Route::post('/manage/goods/delete', 'GoodsMTController@goodsDelete');

// ㄴ 학습 플래너 관리
Route::match(['get', 'post'], '/manage/learning', 'LearningMTController@calendar');
// ㄴ 학습 시작 시간 저장하기
Route::post('/manage/learning/study/time/insert', 'LearningMTController@studyTimeInsert');
// ㄴ 학습 시작 시간 가져오기.
Route::post('/manage/learning/study/time/select', 'LearningMTController@studyTimeSelect');
// ㄴ 학습 시작 시간 삭제
Route::post('/manage/learning/study/time/delete', 'LearningMTController@studyTimeDelete');
// ㄴ  과목 선택시 > 시리즈 선택
Route::post('/manage/learning/subject/series/select', 'LearningMTController@subjectSeriesSelect');
// ㄴ 시간표에 추가하기
Route::post('/manage/learning/student/lecture/insert', 'LearningMTController@studentLectureInsert');
// ㄴ 학생의 학습시간표 가져오기.
Route::post('/manage/learning/study/planner/select', 'LearningMTController@studyPlannerSelect');
// ㄴ 미수강, 재수강 리스트 가져오기.
Route::post('/manage/learning/student/do/lecture/select', 'LearningMTController@studentDoLectureSelect');
// ㄴ 미수강 등록, 재수강 등록 기능.
Route::post('/manage/learning/student/lecture/detail/insert', 'LearningMTController@studentLectureDetailInsert');
// ㄴ 강의 / 날짜 이동.
Route::post('/manage/learning/student/lecture/detail/move', 'LearningMTController@studentLectureDetailMove');
// ㄴ 강의 삭제
Route::post('/manage/learning/student/lecture/detail/delete', 'LearningMTController@studentLectureDetailDelete');
// ㄴ 학습 시간표 학생별 중복 제거 조회
Route::post('/manage/learning/study/planner/distinct/select', 'LearningMTController@studyPlannerDistinctSelect');
// ㄴ 학습 시간표 학생별 중복 제거 삭제
Route::post('/manage/learning/study/planner/distinct/delete', 'LearningMTController@studyPlannerDistinctDelete');
// ㄴ 학교 등록
Route::post('/manage/learning/school/insert', 'LearningMTController@schoolInsert');


// 시스템 관리
// 소속 / 팀 관리
Route::get('/manage/systemteam', 'SystemTeamMTController@team');
// ㄴ 소속명 확인
Route::post('/manage/systemteam/region/name/chk', 'SystemTeamMTController@regionNameChk');
// ㄴ 팀명 확인
Route::post('/manage/systemteam/team/name/chk', 'SystemTeamMTController@teamNameChk');
// ㄴ 소속 SELECT
Route::post('/manage/systemteam/region/select', 'SystemTeamMTController@regionSelect');
// ㄴ 선생님(총괄 / 팀원 / 삼당 ) SELECT
Route::post('/manage/systemteam/teacher/select', 'SystemTeamMTController@teacherSelect');
// ㄴ 소속 / 팀 / 팀에 선생(직원) INSERT
Route::post('/manage/systemteam/teamgroup/insert', 'SystemTeamMTController@teamGroupInsert');
// ㄴ 소속 / 팀 SELECT
Route::post('/manage/systemteam/teamgroup/select', 'SystemTeamMTController@teamGroupSelect');
// ㄴ 소속 / 팀 지역 정보 SELECT
Route::post('/manage/systemteam/team/area/select', 'SystemTeamMTController@teamAreaSelect');
// ㄴ 소속 / 팀 삭제
Route::post('/manage/systemteam/team/delete', 'SystemTeamMTController@teamDelete');
// ㄴ 팀 통합
Route::post('/manage/systemteam/team/merge/insert', 'SystemTeamMTController@teamMergeInsert');
// ㄴ 학교 소속
Route::get('/manage/systemteam/school/region', 'SystemTeamMTController@schoolRegion');
// ㄴ 학교 소속 검색
Route::post('/manage/systemteam/school/search', 'SystemTeamMTController@schoolSearch');

// 공통 코드(분류) 관리
Route::get('/manage/code', 'CodeMTController@code');
// ㄴ 공통 코드 조회
Route::post('/manage/code/select', 'CodeMTController@codeSelect');
// ㄴ 공통 코드 등록
Route::post('/manage/code/insert', 'CodeMTController@codeInsert');
// ㄴ 공통 코드 삭제
Route::post('/manage/code/delete', 'CodeMTController@codeDelete');
// ㄴ 코드 다대다 연결
Route::post('/manage/code/connect/insert', 'CodeMTController@codeConnectInsert');
// ㄴ 코드 다대다 조회
Route::post('/manage/code/connect/select', 'CodeMTController@codeConnectSelect');



//관리자 계정 관리 52P ~ 54P
// ㄴ 관리자 계정 목록
Route::get('/manage/systemadmin', 'SystemAdminMTController@admin');
// ㄴ 관리자 유저그룹 가져오기.
Route::post('/manage/systemadmin/usergroup', 'SystemAdminMTController@usergroup');
// ㄴ 관리자 계정 리스트 가져오기.
Route::post('/manage/systemadmin/select', 'SystemAdminMTController@adminlist');
// ㄴ 관리자 계정 등록 / 수정
Route::post('/manage/systemadmin/insert', 'SystemAdminMTController@insert');
// ㄴ 관리자 계정 삭제
Route::post('/manage/systemadmin/delete', 'SystemAdminMTController@delete');
// ㄴ 관리자 그룹에 따른 메뉴 가져오기
Route::post('/manage/systemadmin/menu/select', 'SystemAdminMTController@menuSelect');
// ㄴ 관리자 그룹 추가.
Route::post('/manage/systemadmin/groupinsert', 'SystemAdminMTController@groupinsert');


//권한 관리 56P ~ 58P
// ㄴ 권한 관리 목록
Route::get('/manage/systemauthority', 'SystemAuthorityMTController@authority');
// ㄴ 권한 / 그룹 가져오기.
Route::post('/manage/systemauthority/select', 'SystemAuthorityMTController@authoritylist');
// ㄴ 권한 / 그룹 등록 / 수정
Route::post('/manage/systemauthority/insert', 'SystemAuthorityMTController@insert');
// ㄴ 권한 / 그룹 삭제
Route::post('/manage/systemauthority/delete', 'SystemAuthorityMTController@delete');

//선생님 권한관리
Route::get('/manage/systemauthority/teacher', 'SystemAuthorityMTController@teacher');
// ㄴ 선생님 권한관리 리스트 카운트 가져오기.
Route::post('/manage/systemauthority/teacher/list/cnt/select', 'SystemAuthorityMTController@teacherListCntSelect');
// ㄴ 선생님 권한관리 리스트 가져오기.
Route::post('/manage/systemauthority/teacher/list/select', 'SystemAuthorityMTController@teacherLectureSeriesSelect');
// ㄴ 선생님 권한관리 권한 저장.
Route::post('/manage/systemauthority/teacher/permission/insert/update', 'SystemAuthorityMTController@teacherLecturePermissionInsertUpdate');
// ㄴ 선생님 권한관리 권한 조회.
Route::post('/manage/systemauthority/teacher/permission/select', 'SystemAuthorityMTController@teacherLecturePermissionSelect');
// ㄴ 선생님 권한관리 권한 삭제.
Route::post('/manage/systemauthority/teacher/permission/delete', 'SystemAuthorityMTController@teacherLecturePermissionDelete');

// 메뉴 관리 47P ~ 49P
Route::get('/manage/menu', 'MenuMTController@menulist');
// ㄴ 메뉴 이름 추가
Route::post('/manage/menu/insert', 'MenuMTController@insert');
// ㄴ 메뉴 수정
Route::post('/manage/menu/update', 'MenuMTController@update');
// ㄴ 메뉴 리스트 가져오기
Route::post('/manage/menu/select', 'MenuMTController@select');
// ㄴ 메뉴 그룹 가져오기
Route::post('/manage/menu/groupselect', 'MenuMTController@groupselect');
// ㄴ 메뉴 삭제
Route::post('/manage/menu/delete', 'MenuMTController@delete');
// ㄴ 메뉴 순서 변경
Route::post('/manage/menu/idxupdate', 'MenuMTController@idxupdate');
// ㄴ 메뉴 그룹 노출 변경
Route::post('/manage/menu/groupupdate', 'MenuMTController@groupupdate');


//  공통 디자인 관리
Route::get('/manage/design', 'DesignMTController@design');
// ㄴ 공통 디자인 기본정보입력
Route::post('/manage/design/basicinsert', 'DesignMTController@basicinsert');
// ㄴ 공동 디자인 첨부파일 삭제
Route::post('/manage/design/filedelete', 'DesignMTController@filedelete');


// 학습영상(강좌관리)
// ㄴ 강좌 목록
Route::get('/manage/lecture/list', 'LectureMTController@list');
// ㄴ 강좌 등록
Route::get('/manage/lecture/add', 'LectureMTController@add');
// ㄴ 강좌 등록에 사용되는 CDOE_CONNECT 가져오기
Route::post('/manage/lecture/add/code/connect/select', 'LectureMTController@codeConnectSelect');
// ㄴ 강좌 등록에서 시리즈, 시리즈 하위 등록.
Route::post('/manage/lecture/add/code/insert', 'LectureMTController@codeInsert');
// ㄴ 강좌 등록 강좌 저장.
Route::post('/manage/lecture/add/insert', 'LectureMTController@lectureInsert');
// ㄴ 강좌 썸네일 삭제
Route::post('/manage/lecture/add/thumbnail/delete', 'LectureMTController@thumbnailDelete');
// ㄴ 강좌 삭제
Route::post('/manage/lecture/add/delete', 'LectureMTController@lectureDelete');
// ㄴ 강좌 리스트 SELECT
Route::post('/manage/lecture/list/select', 'LectureMTController@lectureSelect');
// ㄴ 강좌 리스트 사용 여부 변경
Route::post('/manage/lecture/list/use/update', 'LectureMTController@lectureUseUpdate');
// ㄴ 강좌 목록
Route::get('/manage/lecture/list_v2', 'LectureMTController@list_v2');
// ㄴ 강좌 등록
Route::get('/manage/lecture/add_v2', 'LectureMTController@add_v2');
// ㄴ 인터렉트 미리보기
Route::post('/manage/lecture/interactive/preview', 'LectureMTController@interactivePreview');

// 문제관리
Route::get('/manage/exam/list', 'ExamMTController@list');
// ㄴ 문제 등록
Route::post('/manage/exam/insert', 'ExamMTController@examInsert');
// ㄴ 문제 일괄등록
Route::post('/manage/exam/batch/upload', 'ExamMTController@examBatchUpload');
// ㄴ 문제 조회
Route::post('/manage/exam/select', 'ExamMTController@examSelect');
// ㄴ 문제 상세 등록.
Route::post('/manage/exam/detail/insert', 'ExamMTController@examDetailInsert');
// ㄴ 문제 상세 조회
Route::post('/manage/exam/detail/select', 'ExamMTController@examDetailSelect');
// ㄴ 문제 상세 컨텐트 등록
Route::post('/manage/exam/detail/content/insert', 'ExamMTController@examDetailContentInsert');
// ㄴ 문제 상세 컨텐트 조회
Route::post('/manage/exam/detail/content/select', 'ExamMTController@examDetailContentSelect');
// ㄴ 문제 삭제
Route::post('/manage/exam/delete', 'ExamMTController@examDelete');
// ㄴ 문제 상세 삭제
Route::post('/manage/exam/detail/delete', 'ExamMTController@examDetailDelete');
// ㄴ 문제 상세 이미지 삭제
Route::post('/manage/exam/detail/img/delete', 'ExamMTController@examDetailImgDelete');

// ㄴ 강의영상 여러개 업로드
Route::post('/manage/lecture/video/multiple/upload', 'ExamMTController@videoMultipleUpload');
// ㄴ 문제 미리보기.
Route::post('/manage/exam/preview', 'ExamMTController@examPreview');

// 인터렉티브 관리
Route::get('/manage/interactive', 'InteractiveMTController@list');
// ㄴ 인터렉티브 등록.
Route::post('/manage/interactive/insert', 'InteractiveMTController@insert');
// ㄴ 인터렉티브 조회.
Route::post('/manage/interactive/select', 'InteractiveMTController@select');


// 샘플 시간표 관리
Route::get('/manage/sample/timetable', 'SampleTimeTableMTController@list');
// ㄴ 샘플 시간표 그룹 등록
Route::post('/manage/timetable/group/insert', 'SampleTimeTableMTController@timetableGroupInsert');
// ㄴ 샘플 시간표 SELECT
Route::post('/manage/timetable/select', 'SampleTimeTableMTController@timetableSelect');
// ㄴ 샘플 시간표 그룹 SELECT
Route::post('/manage/sample/timetable/group/select', 'SampleTimeTableMTController@timetableGroupSelect');
// ㄴ 옵션에 따른 강좌 SELECT
Route::post('/manage/timetable/lecture/select', 'SampleTimeTableMTController@lectureSelect');
// ㄴ 샘플 시간표 > 강좌 디테일 (강의) SELECT
Route::post('/manage/timetable/lecture/detail/select', 'SampleTimeTableMTController@lectureDetailSelect');
// ㄴ 샘플 시간표 > timetable에 강좌 목록 INSERT
Route::post('/manage/timetable/insert', 'SampleTimeTableMTController@timetableInsert');
// ㄴ 샘플 시간표 > timetable에 강좌 목록 DELETE
Route::post('/manage/timetable/delete', 'SampleTimeTableMTController@timetableDelete');

// 사용자 수강 관리
Route::get('/manage/user/lecture', 'UserLectureMTController@list');
// ㄴ 사용자 조회
Route::post('/manage/user/lecture/user/select', 'UserLectureMTController@userSelect');
// ㄴ 강좌 카운트 가져오기.
Route::post('/manage/user/lecture/cnt/select', 'UserLectureMTController@cntSelect');


// 사용자 결제 관리
Route::get('/manage/user/payment', 'UserPaymentMTController@list');
// ㄴ 사용자 결제 관리 - 결제 리스트 가져오기.
Route::post('/manage/user/payment/select', 'UserPaymentMTController@paymentSelect');
// ㄴ 사용자 결제 관리 상세정보
Route::post('/manage/user/payment/detail', 'UserPaymentMTController@paymentDetail');
// ㄴ 사용자 결제 관리 상세정보 이전 결제내역
Route::post('/manage/user/payment/detail/history/select', 'UserPaymentMTController@paymentHistorySelect');
// ㄴ 부분 결제 정보 업데이트.
Route::post('/manage/user/payment/detail/part/update', 'UserPaymentMTController@paymentPartUpdate');


// 사용자 상담 관리
Route::get('/manage/user/counsel', 'UserCounselMTController@list');


// 상담일지 상세 화면
Route::post('/manage/counsel/detail', 'CounselMTController@detail');
// 상담일지 등록(상세 불러오기) 화면.
Route::post('/manage/counsel/add', 'CounselMTController@add');
// ㄴ 상당 일지 상세 불러오기.
Route::post('/manage/counsel/detail/select', 'CounselMTController@detailSelect');
// ㄴ 상담 일지 상세 저장
Route::post('/manage/counsel/detail/insert', 'CounselMTController@detailInsert');
// ㄴ 상담삭제
Route::post('/manage/counsel/delete', 'CounselMTController@delete');
// 이용권 상담일지 상세 화면
Route::post('/manage/counsel/goods/detail', 'CounselMTController@goodsDetail');
// ㄴ 이용권 상세 - 학생 관리메모 수정.
Route::post('/manage/counsel/manage/memo/update', 'CounselMTController@manageMemoUpdate');




// 기획 페이지 21P ~ 32P
//게시판
// ㄴ 공지사항
Route::get('/manage/boardnotice', 'BoardMTController@notice');
// ㄴ Q&A 자주 묻는 질문
Route::get('/manage/boardfaq', 'BoardMTController@faq');
// ㄴ 시스템/사용문의
Route::get('/manage/boardqna', 'BoardMTController@qna');
// ㄴ 이벤트
Route::get('/manage/boardevent', 'BoardMTController@event');
// ㄴ 응원 메시지
Route::get('/manage/board/support', 'BoardMTController@support');
// 학습 Q&A sdqna
Route::get('/manage/board/sdqna', 'BoardMTController@sdqna');
// 학습자료
Route::get('/manage/board/learning', 'BoardMTController@learning');

// ㄴ 게시판 가져오기 boardlist
Route::post('/manage/board/select', 'BoardMTController@boardSelect');
// ㄴ 게시판 글쓰기 boardwrite
Route::post('/manage/boardwrite', 'BoardMTController@boardInsert');
// ㄴ 게시판 첨부파일 업로드
Route::post('/manage/boardwrite/fileupload', 'BoardMTController@fileupload');
// ㄴ 게시판 첨부파일 삭제
Route::post('/manage/boardwrite/filedelete', 'BoardMTController@filedelete');
// ㄴ 게시판 삭제
Route::post('/manage/boarddelete', 'BoardMTController@boarddelete');
// ㄴ 게시판 응원메시지 학부모 검색
Route::post('/manage/board/support/parent/select', 'BoardMTController@parentSelect');
// ㄴ 게시판 응원메시지 학생 검색
Route::post('/manage/board/support/student/select', 'BoardMTController@studentSelect');
// ㄴ 게시판 복수 삭제.(응원메시지)
Route::post('/manage/board/sel/delete', 'BoardMTController@boardSelDelete');

// 문자 / 알림 관리
Route::get('/manage/alarm', 'AlarmMtController@alarm');
// ㄴ 메시지 목록에 저장
Route::post('/manage/messageinsert', 'AlarmMtController@messageinsert');
// ㄴ 메시지 목록 가져오기
Route::post('/manage/messagelist', 'AlarmMtController@messagelist');
// ㄴ 메시지 목록 수정
Route::post('/manage/messageupdate', 'AlarmMtController@messageupdate');
// ㄴ 메시지 목록 삭제
Route::post('/manage/messagedelete', 'AlarmMtController@messagedelete');
// ㄴ 회원 목록 가져오기
Route::post('/manage/alarm/studentlist', 'AlarmMtController@studentlist');
// ㄴ 학생 seq로 학생과, 학부모 전화번호 가져오기.
Route::post('/manage/alarm/send/user/info', 'AlarmMtController@sendUserInfo');

// 문자 / 발송 통계
Route::get('/manage/alarmstat', 'AlarmMtController@alarmStat');

//알림 전송 / 문자 / 카카오 / 푸시
// ㄴ 문자 발송
Route::post('/manage/send/sms', 'SendMsgMTController@sms');
// ㄴ 문자 최근 발송 내역
Route::post('/manage/send/sms/last', 'SendMsgMTController@smsLastSelect');
// ㄴ 문자 예약 발송 내역
Route::post('/manage/send/sms/reserv', 'SendMsgMTController@smsReservSelect');
// ㄴ 문자 예약 발송 취소
Route::post('/manage/send/sms/reserv/cancel', 'SendMsgMTController@smsReservCancel');
// ㄴ 문자 예약 수정.
Route::post('/manage/send/sms/reserv/update', 'SendMsgMTController@smsReservUpdate');
// ㄴ 문자 발송 통계
Route::post('/manage/send/sms/statistics', 'SendMsgMTController@smsStatistics');
// ㄴ 문자 방송 상세 확인
Route::post('/manage/send/sms/reportdetail', 'SendMsgMTController@smsReportDetail');

// ㄴ 알림톡 발송
Route::post('/manage/send/kakao', 'SendMsgMTController@kakao');
// ㄴ 알림톡 최근 발송 내역
Route::post('/manage/send/kakao/last', 'SendMsgMTController@kakaoLastSelect');
// ㄴ 알림톡 예약 발송 내역
Route::post('/manage/send/kakao/reserv', 'SendMsgMTController@kakaoReservSelect');
// ㄴ 알림톡 예약 취소
Route::post('/manage/send/kakao/reserv/cancel', 'SendMsgMTController@kakaoReservCancel');
Route::post('/manage/send/push', 'SendMsgMTController@push');


// 로그
// ㄴ 로그 저장
Route::post('/manage/log/insert', 'LogMTController@insert');
// ㄴ 로그 select
Route::post('/manage/log/select', 'LogMTController@select');
// ㄴ 로그 (비고) remark update
Route::post('/manage/log/remark/update', 'LogMTController@remarkUpdate');


//결제 내역 관리
// ㄴ 결제 내역 목록
Route::get('/manage/payment/list', 'PaymentMTController@list');

// 마케팅 페이지
Route::get('/popcron/pd', 'MarketingMTController@popcronPdKakao');
Route::get('/popcron/pd/math', 'MarketingMTController@popcronPdMath')->name('popcron.pd.math');
Route::get('/popcron/pd/hanja', 'MarketingMTController@popcronPdHanja')->name('popcron.pd.hanja');
Route::get('/popcron/pd/english', 'MarketingMTController@popcronPdEnglish')->name('popcron.pd.english');

// 관리자 페이지 END
//-----------------------------------------------------
