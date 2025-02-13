@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '예체능 수업')

@section('add_css_js')
    <link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<div class="col mx-0 mb-3 pt-5 row position-relative">
    {{-- 상단 --}}
    <article class="pt-5 px-0">
        <div class="row">
            <div class="col-auto">
                <div class="h-center">
                    <img src="{{ asset('images/yecheung_icon.svg?1') }}" width="72">
                    <span class="cfs-1 fw-semibold align-middle">예체능 수업</span>
                </div>
                <div class="pt-2">
                    <span class="cfs-3 fw-medium">여러가지 학습을 한 눈에 쉽게 알아볼 수 있어요.</span>
                </div>
            </div>
            <div class="col text-end">
                <div class="pt-5">
                    <div class="d-inline-block select-wrap select-icon" style="min-width:200px">
                        <select class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                            style="min-width:260px;padding-top:18px;padding-bottom:18px;">
                            <option value="">학년선택</option>
                            @if (!empty($grade_codes))
                                @foreach ($grade_codes as $grade_code)
                                    <option value="{{ $grade_code->id }}">{{ $grade_code->code_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </article>

    {{-- padding 120px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="pt-lg-4"></div>
    </div>

    <article>
        
        {{-- 분류로 예상 됨. --}}
        <section>
            {{-- Tab --}}
            <div class="row mx-0 mb-5">
                <ul class="col d-inline-flex gap-2 mb-3 px-0">
                    <li>
                      <button type="button" class="btn-ms-primary text-sb-24px rounded-pill scale-text-white px-32">음악</button>
                    </li>
                    <li>
                      <button type="button" class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">미술</button>
                    </li>
                    <li>
                      <button type="button" class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">체육</button>
                    </li>
                    <li>
                        <button type="button" class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">도덕</button>
                    </li>
                    <li>
                        <button type="button" class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">도덕클립</button>
                      </li>
                      <li>
                        <button type="button" class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 px-32 scale-text-gray_05 scale-text-white-hover">미래사회 클립</button>
                      </li>
                </ul>
                <div class="col-auto">
                    <span class="text-sb-24px"><span class="text-danger">총 <span class="text-danger">6</span>개의</span> 수업이 있습니다.</span>
                </div>
            </div>
            {{-- 예체능 수업 list--}}
            <div class="row row-cols-lg-3 mt-1 mx-0">
                <div class="col">
                    <div class="scale-bg-gray_01 rounded-4 p-4">
                        <div class="scale-text-gray_05 text-sb-18px mt-2">
                            <span class="scale-text-gray_05">김팝콘</span> 선생님
                        </div>
                        <div class="row mx-0 mb-2">
                            <div class="col h-center text-sb-24px px-0">
                                [1강] 박과 박자
                            </div>
                            <div class="col h-center justify-content-end">
                                <button class="btn rounded-pill text-sb-20px btn-light scale-bg-white border scale-bg-gray_01-hover py-2 px-3 all-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.3569 12.9389C18.0086 12.4826 18.0086 11.5174 17.3569 11.0612L9.07274 5.26225C8.31319 4.73056 7.26953 5.27395 7.26953 6.2011V17.7989C7.26953 18.7261 8.31319 19.2694 9.07274 18.7378L17.3569 12.9389Z" fill="#222222"/>
                                    </svg>                                        
                                    학습하기
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>
    </article>
    {{-- padding 160px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>



</div>
@endsection