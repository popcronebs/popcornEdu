@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '학습하기')

@section('add_css_js')
<link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<!-- TODO: 확인문제. -->
<!-- TODO: 실력문제. -->

<!-- : 선택 학습 정보 붙여넣기. -->
<!-- : 현재 학습중 -->
<!-- : 오늘의 학습. -->
<!-- : 프로그래스 바 색 변경. -->
<!-- : 시간 업데이트시에 자동으로 동영상시간 업데이트 되도록 수정. -->
<!-- : 드래그로 시간 이동 불가 : 중간에 멈췄다면 멈춘 시점부터 이어보기 -->
<!-- : 측정 시간 : 처음 재생 눌렀을 때부터 문제 다 풀고 제출했을 때 까지 > 본 횟수 상관없고 , 재생-문제 제출 시간 1회 고정입니다 -->
<!-- : 재생 눌렀을때 시작입니다 학습 그만할래요 클릭 시 그 시점부터 이어보기 가능 실력 문제까지 다 풀었을 때 까지 시간 측정 -->
<!-- : 동영상 시청 시간 / 마지막 시간  업데이트. -->
<!-- : IS_LIKE 기능 구현. -->
<!--  : 시청시 시청 시간 카운트.(어떻게 할지) / 우선은 누적시간 마지막 시간 업데이트 -->
<!-- NOTE: 동영상 붙이기. / 우선은 내장되어잇는 비디오로 붙임. -->
<input type="hidden" data-main-student-lecutre-detail-seq value="{{ $st_lecture_detail_seq}}">
<style>
    header,
    footer {
        display: none;
    }
    .score-container + img {
        position: absolute;
        top: 50%;
        left: 50%;
        translate: -50% -50%;
        animation: alertFire 1.8s cubic-bezier(0.68, -0.55, 1.55) forwards;
        z-index: 11;
    }
    #layout_div_content {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FFF6E0;
        background-position: center;
        background-size: cover;

    }
    .score-container{
        z-index: 10;
    }

    @keyframes alertFire {
        0% {
            top: 50%;
        }
        20%{
            top: 50%;

        }
        50% {
            top: -15%;
        }
        100% {
            top: -10%;
        }
    }

    @media (max-width: 1400px) {}
</style>
<div class="col mx-0 row position-relative justify-content-center">
    <div class="score-container">
        <div class="score-head">
            <div class="score-head-title">
                <span>학습 결과</span>
            </div>
        </div>
        <div class="score-body">
            <div class="score-grid">
                <div class="score-grid-row">
                    <div class="score-grid-item">
                        <span class="">1번</span>
                        <span class="score-stars">
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="score-grid-item">
                        <span class="">도전</span>
                        <span class="score-circle">
                            <span class=""></span>
                        </span>
                    </div>
                </div>
                <div class="score-grid-row">
                    <div class="score-grid-item">
                        <span class="">2번</span>
                        <span class="score-triangle">
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="score-grid-item">
                        <span class="">도전</span>
                        <span class="score-circle">
                            <span class=""></span>
                        </span>
                        <span class="">유사</span>
                        <span class="score-circle">
                            <span class=""></span>
                        </span>
                    </div>
                </div>
                <div class="score-grid-row">
                    <div class="score-grid-item">
                        <span class="">3번</span>
                        <span class="score-triangle">
                            <span class=""></span>
                        </span>
                    </div>
                    <div class="score-grid-item">
                        <span class="">도전</span>
                        <span class="score-triangle">
                            <span class=""></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="score-grid-final-result">
                <div class="score-grid-final-result-title">
                    <span>기본 문제</span>
                </div>
                <div class="score-grid-final-result-score">
                    <div class="">
                        <span class="text-gray">맞힌 문제</span>
                        <span class="mx-2 border dividing"></span>
                        <span class="">3 문제</span>
                    </div>
                    <div class="">
                        <span class="text-gray">틀린 문제</span>
                        <span class="mx-2 border dividing"></span>
                        <span>2 문제</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="score-footer row">
            <button type="button" class="msg_btn2 btn btn-lg btn-light ctext-gc1 col text-b-24px">학습 끝내기</button>
            <button type="button" class="msg_btn1 btn btn-lg btn-primary-y col text-b-24px py-3">오답노트 풀러가기</button>
        </div>
    </div>
    <img src="{{ asset('images/alert_fire_score.svg') }}" alt="" style="width: 240px;">
</div>
{{-- padding 160px --}}
<!-- <div>
    <div class="py-lg-5"></div>
    <div class="py-lg-4"></div>
    <div class="pt-lg-3"></div>
</div> -->
<script>
    // 홈페이지 나가려고 할때 감지해서 studyVideoTimeUpdate펑션 실행.
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('beforeunload', function(e) {
            studyVideoPause();
            studyVideoTimeUpdate();
            document.querySelectorAll('.video-cont-tab.cursor-pointer li:not([hidden])').forEach((span, index) => {
                span.querySelector('span').textContent = index + 1;
            });
        });
        asideRight();
        const listBody = document.querySelector('.aside-right .learning-list-body');
        const customScrollbar = document.querySelector('.custom-scrollbar');
        const scrollbarThumb = document.querySelector('.custom-scrollbar .scrollbar-thumb');
        const scrollbarTrack = document.querySelector('.custom-scrollbar .scrollbar-track');

        function updateScrollbarThumb() {
            const thumbHeight = listBody.clientHeight * (listBody.clientHeight / listBody.scrollHeight);
            scrollbarThumb.style.height = `${thumbHeight}px`;

        }

        function handleScroll() {
            const scrollTop = listBody.scrollTop;
            const scrollRatio = scrollTop / (listBody.scrollHeight - listBody.clientHeight);
            const thumbTop = scrollRatio * (listBody.clientHeight - scrollbarThumb.clientHeight);
            console.log(scrollbarTrack.clientHeight / scrollbarThumb.clientHeight);
            scrollbarThumb.style.transform = `translateY(${thumbTop}px)`;
            customScrollbar.style.transform = `translateY(${thumbTop * ((scrollbarTrack.clientHeight / scrollbarThumb.clientHeight) - 0.02)}px)`;
            customScrollbar.style.opacity = 1;
            // 추가적인 스크롤 처리 로직을 여기에 작성
            clearTimeout(customScrollbar.hideTimeout);
            customScrollbar.hideTimeout = setTimeout(() => {
                customScrollbar.style.opacity = 0;
            }, 1000);
        }
        listBody.addEventListener('scroll', updateScrollbarThumb);
        listBody.addEventListener('scroll', handleScroll);
        window.addEventListener('resize', updateScrollbarThumb);
        updateScrollbarThumb();

        let isDragging = false;
        let startY;
        let startTop;

        scrollbarThumb.addEventListener('mousedown', function(e) {
            isDragging = true;
            startY = e.clientY;
            startTop = parseInt(window.getComputedStyle(scrollbarThumb).top, 10);
            document.body.style.userSelect = 'none';
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            const deltaY = e.clientY - startY;
            const newTop = startTop + deltaY;
            const maxTop = listBody.clientHeight - scrollbarThumb.clientHeight;
            const scrollRatio = newTop / maxTop;
            listBody.scrollTop = scrollRatio * (listBody.scrollHeight - listBody.clientHeight);
        });

        document.addEventListener('mouseup', function() {
            isDragging = false;
            document.body.style.userSelect = '';
        });
    });

    //시작
    document.addEventListener('DOMContentLoaded', function() {
        studyVideoGetTodayStudy();
    });

    function asideRight() {
        console.log(lectureVideo.clientHeight)
        document.querySelector('.learning-list-body').style.height = (lectureVideo.clientHeight - 100) + 'px';
    }

    window.addEventListener('resize', function() {
        asideRight();
    });

    function studyVideoAsideTab(vthis) {
        // 나머지 버튼 비활성화
        document.querySelectorAll('[data-btn-study-video-main-tab]').forEach(function(item) {
            item.classList.remove('active');
        });
        // 활성화
        vthis.classList.add('active');
    }

    // 학습 그만하기 / 뒤로가기
    function studyVideoExit() {
        // 뒤로가기
        const msg1 = '<div class="text-b-28px">아직 학습이 끝나지 않았어요.</div>';
        const msg2 = '<div class="text-b-28px text-danger pb-4 pt-3">정말 학습을 그만할건가요?</div>';
        sAlert('', msg1 + msg2, 3, function() {
                // 배경(음영) 삭제.
                document.querySelector('.modal-backdrop').remove();
            }, function() {

                //그만둘경우 저장 지점 저장.
                studyVideoPause();
                studyVideoTimeUpdate();

                sessionStorage.setItem('isBackNavigation', 'true');
                history.back();
            },
            '더 해볼게요.',
            '네,그만할게요.');
        // 배경음영.
        const myModal = new bootstrap.Modal(document.querySelector('#system_alert .modal'), {});
        myModal.show();

    }

    // 하단 섹션 탭 버튼 클릭 / 오늘의 학습 / 현재 학습 중
    function studyVideoSectionBtmTab(vthis) {
        // data-btn-study-video-btm-tab 모두 비활성화
        document.querySelectorAll('[data-btn-study-video-btm-tab]').forEach(function(item) {
            item.classList.remove('active');
        });
        // 활성화
        vthis.classList.add('active');

        // 리스트 불러오기.
        studyVideoGetTodayStudy();
    }

    // 좋아요 클릭.
    function studyVideoClickLike() {
        const inp_is_like = document.querySelector('[data-inp-is-like]').value;
        const st_lecture_detail_seq = document.querySelector('[data-main-student-lecutre-detail-seq]').value;
        let is_like = 'Y';
        if (inp_is_like == 'Y') is_like = 'N';

        const page = "/student/study/video/like/update";
        const parameter = {
            'st_lecture_detail_seq': st_lecture_detail_seq,
            'is_like': is_like
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                toast('좋아요 설정이 변경되었습니다.');
                // 좋아요 반대로.
                // data-btn-is-like 버튼 안에 2개의 img hidden 반대로 변경.
                document.querySelector('[data-inp-is-like]').value = is_like;
                const btn_like = document.querySelector('[data-btn-is-like]');
                const img_like_els = btn_like.querySelectorAll('img');
                img_like_els.forEach(function(item) {
                    item.hidden = !item.hidden;
                });
            } else {}
        })

    }
    // 비디오 멈춤.
    function studyVideoPause() {
        const video = document.querySelector('#lectureVideo');
        video.pause();
    }

    // 출선(학습시작)
    function attendInsert(callback) {
        const sel_date = new Date().format('yyyy-MM-dd');
        const page = "/student/study/start/attend";
        const parameter = {
            sel_date: sel_date
        };

        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                // alert('출석이 완료되었습니다.');
                if (callback != undefined) callback();
            } else {
                // toast('다시 시도해주세요');
            }
        })
    }

    // 동영상 완료시
    function studyVideoComplete() {
        const st_lecture_detail_seq = document.querySelector('[data-main-student-lecutre-detail-seq]').value;
        const page = "/student/study/video/complete/update";
        const parameter = {
            st_lecture_detail_seq: st_lecture_detail_seq
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                // alert('학습이 완료되었습니다.');
            } else {}
        });
    }
</script>
@endsection
