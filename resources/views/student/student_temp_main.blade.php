@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '대쉬보드')
{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
{{-- 개발 임시 페이지 개발시 추가 --}}
<style>
th, td{
    border: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color);
}
</style>
<div class="row">
    <span>진행상황 / 예정일 = 완료예정일</span>
    <div class="ps-4 d-flex flex-column pb-5 pt-5">
        <table class="table table-bordered table-striped" >
            <thead {{ (session()->get('login_type') == 'student' || session()->get('login_type') == 'parent') ? 'hidden':''}}>
                <tr>
                    <th class="text-primary">종류</th>
                    <th class="text-primary">선생님 페이지</th>
                    <th class="text-primary">퍼블</th>
                    <th class="text-primary">기능</th>
                    <th class="text-primary">퍼블예정일</th>
                    <th class="text-primary">기능예정일</th>
                    <th class="text-primary">비고/잔업</th>
                </tr>
            </thead>
            <tbody {{ (session()->get('login_type') == 'student' || session()->get('login_type') == 'parent') ? 'hidden':''}}>
                {{-- 로그인 방과후 --}}
                <tr>
                    <td>방과후</td>
                    <td><a href="/teacher/after/first/login">01_로그인</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>4/5</td>
                    <td>
                        <div hidden>
                            1. 신규 선생님 등록 ?.
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><a href="/teacher/first/login">01_로그인</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>5/9</td>
                    <td>5/9</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td><a href="/teacher/main">02_소속선택 </a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>5/28</td>
                    <td>
                        <div hidden>
                            1. 오늘 현황 기준일을 확인.
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>방과후</td>
                    <td><a href="/teacher/main/after">02_소속선택 / 03_대시보드_출석체크</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>4/8</td>
                    <td>5/29</td>
                    <td>
                        <div hidden>
                            1. 수업종료시 알림전송 미 구현.(알림톡 없음)
                        </div>
                    </td>
                </tr>
                {{-- 대시보드 --}}
                <tr>
                    <td></td>
                    <td><a href="/teacher/dashboard">03_대시보드</a></td>
                    <td class="text-danger">진행<br>(추가 추후)</td>
                    <td class="text-danger">진행<br>(추가 추후)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                {{-- 쪽지함 --}}
                <tr>
                    <td></td>
                    <td><a href="/teacher/messenger">04_쪽지함</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>4/5</td>
                    <td>
                    </td>
                </tr>
                {{-- 학생정보관리 --}}
                <tr>
                    <td></td>
                    <td><a href="/teacher/student">05_학생정보관리</a></td>
                    <td class="text-danger">부분완료</td>
                    <td class="text-danger">부분완료</td>
                    <td>6/25</td>
                    <td>6/25</td>
                    <td>1. 결제, 2. 포인트 관리화면의 거래취소 및 지급차감 </td>
                </tr>
                <tr>
                    <td>방과후</td>
                    <td><a href="/teacher/student/after">05_학생정보관리</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>6/13</td>
                    <td>6/13</td>
                    <td></td>
                </tr>
                {{-- 선생님 정보관리 --}}
                <tr>
                    <td></td>
                    <td><a href="/teacher/manage">06_선생님 정보관리</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>5/8</td>
                    <td>5/8</td>
                    <td>
                        <div hidden>
                            1. 문자/알림톡, 사이트팝업?
                            = 선생님 문자전송은 미구현.
                        </div>
                    </td>
                </tr>
                {{-- 소속 및 팀관리 --}}
                <tr>
                    <td></td>
                    <td><a href="/manage/systemteam">07_소속 및 팀관리</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>4/17</td>
                    <td>-</td>
                    <td>
                    </td>
                </tr>
                {{-- 학생 수강관리 --}}
                <tr>
                    <td></td>
                    <td><a href="/manage/user/lecture">08_학생 수강관리</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>4/9</td>
                    <td>4/15</td>
                    <td></td>
                </tr>
                {{-- 학습 상담관리 --}}
                <tr>
                    <td></td>
                    <td><a href="/teacher/counsel">09_학습 상담관리</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>4/25</td>
                    <td>4/23</td>
                    <td></td>
                </tr>
                {{-- 이용권 상담관리 --}}
                <tr>
                    <td></td>
                    <td><a href="/teacher/counsel/goods">10_이용권 상담관리</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>5/1</td>
                    <td>5/1</td>
                    <td>2. 자동으로 다음 상담이 잡히는 기능 crontab 기능 제외</td>
                </tr>
                {{-- 결제관리 --}}
                <tr>
                    <td></td>
                    <td><a href="/manage/user/payment">11_결제관리</a></td>
                    <td class="text-danger">부분완료</td>
                    <td class="text-danger">부분완료</td>
                    <td>5/14</td>
                    <td>5/14</td>
                    <td>1. 실제 결제부분 2. 이용권 변경등.</td>
                </tr>
                {{-- 수업관리/방과후 --}}
                <tr>
                    <td>방과후</td>
                    <td><a href="/teacher/after/class/management">12_수업관리</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>5/3</td>
                    <td>9/13</td>
                    <td>1. 보강일정 발송 2. 상세보기</td>
                </tr>
                {{-- 학습관리/방과후 --}}
                <tr>
                    <td>방과후</td>
                    <td><a href="/teacher/after/learning/management">13_학습관리</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>5/8</td>
                    <td>9/11</td>
                    <td>
                    </td>
                </tr>
                {{-- 학습플래너 --}}
                <tr>
                    <td></td>
                    <td><a href="/manage/learning?student_seq=2714">14_학습플래너</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>4/4</td>
                    <td></td>
                </tr>
                {{-- 문자 및 알림관리 --}}
                <td></td>
                <td><a href="/manage/alarm">15_문자 및 알림관리</a></td>
                <td class="text-danger">완료</td>
                <td class="text-danger">완료</td>
                <td>4/11</td>
                <td>4/15</td>
                <td></td>
                </tr>
                {{-- 마이페이지 --}}
                <tr>
                    <td></td>
                    <td><a href="/teacher/member/info">99_마이페이지</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>4/12</td>
                    <td>4/12</td>
                    <td></td>
                </tr>
                {{-- 공지사항 --}}
                <tr>
                    <td></td>
                    <td><a href="/manage/boardnotice">99_공지사항</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>4/15</td>
                    <td>4/15</td>
                    <td></td>
                </tr>
                {{-- 알림센터 --}}
                <tr>
                    <td></td>
                    <td><a href="/teacher/push/list">99_알림센터</a></td>
                    <td class="text-danger">부분완료</td>
                    <td class="text-danger">부분완료</td>
                    <td>4/15</td>
                    <td>4/15</td>
                    <td></td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th class="text-primary pt-5" colspan="2">학생 페이지</th>
                    <th class="text-primary">퍼블</th>
                    <th class="text-primary">기능</th>
                    <th class="text-primary">퍼블예정일</th>
                    <th class="text-primary">기능예정일</th>
                    <th class="text-primary">비고/잔업</th>
                </tr>
            </thead>
            <tbody data="#학생페이지">
                <tr>
                    <td colspan="2"><a href="/student/main">00_메인페이지</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>7/3</td>
                    <td></td>
                </tr>
                {{-- 학습플래너 --}}
                <tr>
                    <td colspan="2"><a href="/manage/learning">00_M_01_학습플래너</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>3/29</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/student/wrong/note">00_M_02_오답노트</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td class="">8/27</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/student/my/study">00_M_03_나의학습</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>3/27</td>
                    <td>7/29</td>
                    <td>1. 월간현황, 학습자료 제외</td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/student/my/score">00_M_04_내 성적표</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>8/28</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/student/study/point">00_M_99_학습포인트</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>8/12</td>
                    <td>1. 활동 지수에 관련된 포인트 습득 및 SELECT.</td>
                </tr>
                {{-- 학교공부 --}}
                <tr>
                    <td colspan="2"><a href="/student/school/study">01_학교공부</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>3/25</td>
                    <td>7/4</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/student/unit/test">02_단원평가</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>9/2</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/student/event">05_이벤트</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>-</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/student/all/study">07_전체학습보기</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-info">미정</td>
                    <td>-</td>
                    <td>9/3</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="ps-4" colspan="2"><a href="/student/all/study/physical">ㄴ 예체능 수업</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-info">미정</td>
                    <td>-</td>
                    <td>9/4</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="ps-4" colspan="2"><a href="/student/all/study/ability/up">ㄴ 실력키우기</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-info">미정</td>
                    <td>-</td>
                    <td>9/5</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="ps-4" colspan="2"><a href="/student/all/study/creative/experience">ㄴ 창의체험</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-info">미정</td>
                    <td>-</td>
                    <td>9/10</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="ps-4" colspan="2"><a href="/student/all/study/unit/summary">ㄴ 단원별 요점정리</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-info">미정</td>
                    <td>-</td>
                    <td>9/12</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/student/notice">99_공지사항</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>-</td>
                    <td></td>
                </tr>
                {{-- 회원정보 --}}
                <tr>
                    <td colspan="2"><a href="/student/member/info">99_회원정보</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>-</td>
                    <td>캐릭터 선택은 화면이 없음. 미정이라고 답변.</td>
                </tr>
                {{-- 쪽지함 --}}
                <tr>
                    <td colspan="2"><a href="/teacher/messenger">99_쪽지함</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>-</td>
                    <td>3/29</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/student/book">??_교재</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-info">미정</td>
                    <td>-</td>
                    <td>-</td>
                    <td>디자인에서 사라짐.</td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th class="text-primary pt-5" colspan="2">학부모 페이지</th>
                    <th class="text-primary">퍼블</th>
                    <th class="text-primary">기능</th>
                    <th class="text-primary">퍼블예정일</th>
                    <th class="text-primary">기능예정일</th>
                    <th class="text-primary">비고/잔업</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2"><a href="/parent/login">학부모 로그인 페이지</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>7/8</td>
                    <td>7/8</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/parent/index">01_인덱스</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>7/8</td>
                    <td>7/29</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/parent/payment">02_결제(이용권) 관리</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-info"></td>
                    <td>7/23</td>
                    <td>-</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/parent/noti/settings">03_알림설정</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>7/8</td>
                    <td>7/30</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/parent/member/info">04_회원수정</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>7/9</td>
                    <td>8/1</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/parent/push/list">05_알림센터</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>7/8</td>
                    <td>8/5</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/teacher/messenger">06_쪽지함</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>7/10</td>
                    <td>7/10</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/manage/learning">06_학습플래너</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>7/10</td>
                    <td>7/10</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/parent/learning">07_학습관리_240418</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>7/16</td>
                    <td>8/7</td>
                    <td>1. 월별 / 2. 재수강 > 학습결과 부분></td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/parent/evaluation">08_평가관리_240621</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>7/18</td>
                    <td>8/29</td>
                    <td>1. 오답노트 출력하기 기능.</td>
                </tr>
                <tr>
                    <td colspan="2"><a onclick="document.querySelector('[onclick=\'parentLayoutProfileOpen(this);\']').click();">99_GNB</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-danger">완료</td>
                    <td>7/11</td>
                    <td>7/11</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="/parent/test">99_재등록 알림 팝업</a>
                        <a href="/parent/test1">전국평가</a>
                        <a href="/parent/test2">결제팝업</a>
                    </td>
                    <td class="text-danger">완료</td>
                    <td class="text-info"></td>
                    <td>7/18</td>
                    <td>-</td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><a href="/parent/test3">99_시스템 사용 문의</a></td>
                    <td class="text-danger">완료</td>
                    <td class="text-info"></td>
                    <td>7/18</td>
                    <td>-</td>
                    <td></td>
                </tr>

            </tbody>
        </table>
    </div>

</div>
@endsection
