@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    소속 선택
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<style>
    .progress-circle {
    width: 100px;
    height: 100px;
    transform: rotate(-180deg);
    border-radius: 50%;
    background: conic-gradient(
    #70C393 0%,
    #70C393 0%,
    #f6f3f3 0%,
    #f6f3f3 100%
    );
  }
  .progress-circle span {
    
    position: absolute;
    width: 90%;
    height: 90%;
    display: flex;
    justify-content: center;
    align-items: center;
    transform: rotate(180deg);
    font-size: 0.75rem;
    font-weight: 600;
    color: #0b7833;
    margin:auto;
    top:0px;
    bottom:0px;
    left:0px;
    right:0px;
    background: #ffffff;
    z-index: 2;
  }
  .progress-circle .round-br {
    position: absolute;
    width: 95%;
    height: 95%;
    margin:auto;
    top:0px;
    bottom:0px;
    left:0px;
    right:0px;
    background: #999494;
    z-index: 1;
  }
</style>
<div class="col pe-3 ps-3 mb-3 pt-3 row position-relative">
    <div class="row m-0 p-0 ps-3">
        {{-- 선생님 현황 --}}
        <div class="row col-8 pe-4">
            <div class="border p-4 px-5">
                <div>
                    2023년 07월 01일 월요일 오후 3시 45분 | {{ session()->get('teach_name') }} 선생님의 현황입니다.
                </div>
                <div class="row gap-2 mt-3">
                    <div class="border col rounded-4 p-4 d-flex flex-wrap align-items-cneter justify-content-center bg-light">
                        <div class="progress-circle">
                            <span class="fs-5 rounded-circle bg-white">0%</span>
                        </div>
                        <div class="mt-3 fs-5">오늘 학습 완료율</div>
                    </div>
    
                    <div class="border col rounded-4 p-4 row align-items-center justify-content-center text-center m-0 bg-light">
                        <div class="fs-4">
                            <span>5</span>명
                        </div>
                        <div>
                            <div class="fs-5">오늘</div>
                            <div class="fs-5">상담(예정) 학생</div>
                        </div>
                    </div>
                    <div class="border col rounded-4 p-4 row align-items-center justify-content-center text-center m-0 bg-light">
                        <div class="fs-4">
                            <span>3</span>명
                        </div>
                        <div>
                            <div class="fs-5">재등록</div>
                            <div class="fs-5">임박 학생수</div>
                            <div class="fs-6 text-secondary">*잔여 1개월 미만</div>
                        </div>
                    </div>
                    <div class="border col rounded-4 p-4 row align-items-center justify-content-center text-center m-0 bg-light">
                        <div class="fs-4">
                            <span>150</span>명
                        </div>
                        <div>
                            <div class="fs-5">현재 등록된</div>
                            <div class="fs-5">학생수</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- 업무 전체 공지 --}}
        <div class="row col-4">
            <table class="table table-bordered border mb-0">
                <thead class="table-light">
                    <td colspan="2">
                        업무 전체공지
                        <a href="#" class="float-end text-decoration-none text-secondary fw-bold">더보기</a>
                    </td>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span class="text-danger">(NEW)</span>
                            (중요) 상반기 실적종합안내
                        </td>
                        <td class="text-end">23.06.31</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- 학습신호등 --}}
        <div class="row col-8 pe-4 mt-3">
            <table class="table table-bordered border mb-0">
                <thead class="table-light">
                    <tr>
                        <td colspan="6"> 
                            학습 신호등 (34명 학습중 / 50명 학습완료 / 17명 접속안함) 
                            <a href="#" class="float-end text-decoration-none text-secondary fw-bold">더보기</a>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td>구분</td>
                        <td>이름</td>
                        <td>수행률</td>
                        <td>출석률</td>
                        <td>접속현황</td>
                        <td>완료여부</td>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr>
                        <td>초3/부산한국초</td>
                        <td>최학생</td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="row justify-content-center">
                                <div class="bg-success rounded-circle" style="width:24px;height:24px"></div>
                            </div>
                        </td>
                        <td>
                            <div class="row justify-content-center">
                                <div class="bg-warning rounded-circle" style="width:24px;height:24px"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- 학습 문의사항 --}}
        <div class="row col-4 mt-3">
            <table class="table table-bordered border mb-0">
                <thead class="table-light">
                    <td colspan="2"> 
                        학습 문의사항 
                        <a href="#" class="float-end text-decoration-none text-secondary fw-bold">더보기</a>
                    </td>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span class="text-danger">(NEW)</span>
                            (중요) 상반기 실적종합안내
                        </td>
                        <td class="text-end">23.06.31</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    // vanilla js
    //ready
    window.addEventListener('DOMContentLoaded', function() {
        teachMainAnimate(75);
    });
    function teachMainAnimate(percent_max){
        // 시작 퍼센트
        let percent = 0;

        // 시작 시간 간격
        let interval = 50;

        // 함수를 재귀적으로 호출합니다.
        function animate() {
            // 퍼센트를 증가시킵니다.
            percent++;

            // teachMainCircleProgress 함수를 호출하여 원형 진행률 표시기를 업데이트합니다.
            teachMainCircleProgress(percent);

            // 퍼센트가 percent_max 이상이면 함수 호출을 중지합니다.
            if (percent < percent_max) {
                // 시간 간격을 줄입니다.
                interval *= 0.9;

                // setTimeout을 사용하여 animate 함수를 재귀적으로 호출합니다.
                setTimeout(animate, interval);
            }
        }

        // animate 함수를 처음 호출합니다.
        animate();
    }
    function teachMainCircleProgress(percent){
        document.querySelector('.progress-circle').style.background = 'conic-gradient( #70C393 0%, #70C393 '+percent+'%, #f6f3f3 '+percent+'%, #f6f3f3 100% )';
        document.querySelector('.progress-circle span').innerHTML = percent+'%';
    }
</script>
@endsection